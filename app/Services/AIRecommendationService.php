<?php

namespace App\Services;

use App\Models\Property;
use App\Models\User;
use App\Models\Booking;
use App\Models\Review;
use App\Models\SavedSearch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AIRecommendationService
{
    protected $weights = [
        'location_similarity' => 0.25,
        'price_compatibility' => 0.20,
        'amenity_match' => 0.15,
        'review_score' => 0.15,
        'booking_history' => 0.15,
        'user_preferences' => 0.10,
    ];

    /**
     * Get personalized property recommendations for a user
     */
    public function getPersonalizedRecommendations(User $user, int $limit = 10): Collection
    {
        $cacheKey = "recommendations_user_{$user->id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($user, $limit) {
            // Get user's booking history and preferences
            $userProfile = $this->buildUserProfile($user);
            
            // Get all available properties
            $properties = Property::where('is_active', true)
                ->where('user_id', '!=', $user->id) // Exclude user's own properties
                ->with(['reviews', 'user', 'images'])
                ->get();

            // Calculate recommendation scores
            $scoredProperties = $properties->map(function ($property) use ($userProfile) {
                $score = $this->calculateRecommendationScore($property, $userProfile);
                $property->recommendation_score = $score;
                return $property;
            });

            // Sort by score and return top recommendations
            return $scoredProperties
                ->sortByDesc('recommendation_score')
                ->take($limit)
                ->values();
        });
    }

    /**
     * Get similar properties based on a given property
     */
    public function getSimilarProperties(Property $property, int $limit = 8): Collection
    {
        $cacheKey = "similar_properties_{$property->id}";
        
        return Cache::remember($cacheKey, 7200, function () use ($property, $limit) {
            $properties = Property::where('is_active', true)
                ->where('id', '!=', $property->id)
                ->with(['reviews', 'user', 'images'])
                ->get();

            $scoredProperties = $properties->map(function ($similarProperty) use ($property) {
                $score = $this->calculateSimilarityScore($property, $similarProperty);
                $similarProperty->similarity_score = $score;
                return $similarProperty;
            });

            return $scoredProperties
                ->sortByDesc('similarity_score')
                ->take($limit)
                ->values();
        });
    }

    /**
     * Get trending properties based on recent activity
     */
    public function getTrendingProperties(int $limit = 10): Collection
    {
        $cacheKey = "trending_properties";
        
        return Cache::remember($cacheKey, 1800, function () use ($limit) {
            $trendingData = DB::table('properties')
                ->select('properties.*')
                ->selectRaw('
                    (SELECT COUNT(*) FROM bookings WHERE bookings.property_id = properties.id AND bookings.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as recent_bookings,
                    (SELECT COUNT(*) FROM reviews WHERE reviews.property_id = properties.id AND reviews.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as recent_reviews,
                    (SELECT AVG(rating) FROM reviews WHERE reviews.property_id = properties.id) as avg_rating,
                    (SELECT COUNT(*) FROM property_views WHERE property_views.property_id = properties.id AND property_views.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as recent_views
                ')
                ->where('is_active', true)
                ->havingRaw('recent_bookings > 0 OR recent_reviews > 0 OR recent_views > 5')
                ->orderByRaw('(recent_bookings * 3 + recent_reviews * 2 + recent_views * 1 + COALESCE(avg_rating, 0) * 2) DESC')
                ->limit($limit)
                ->get();

            return Property::whereIn('id', $trendingData->pluck('id'))
                ->with(['reviews', 'user', 'images'])
                ->get();
        });
    }

    /**
     * Get recommendations based on user's saved searches
     */
    public function getRecommendationsFromSavedSearches(User $user, int $limit = 6): Collection
    {
        $savedSearches = $user->savedSearches()->where('is_active', true)->get();
        
        if ($savedSearches->isEmpty()) {
            return collect();
        }

        $recommendations = collect();
        
        foreach ($savedSearches as $search) {
            $properties = $this->findPropertiesMatchingSavedSearch($search, $limit);
            $recommendations = $recommendations->merge($properties);
        }

        return $recommendations->unique('id')->take($limit);
    }

    /**
     * Get price predictions for a property
     */
    public function getPricePrediction(Property $property): array
    {
        $similarProperties = $this->getSimilarProperties($property, 20);
        
        if ($similarProperties->isEmpty()) {
            return [
                'suggested_price' => $property->price_per_night,
                'confidence' => 'low',
                'market_analysis' => 'Insufficient data for analysis'
            ];
        }

        $prices = $similarProperties->pluck('price_per_night');
        $avgPrice = $prices->avg();
        $medianPrice = $prices->median();
        $currentPrice = $property->price_per_night;

        // Calculate price recommendation
        $suggestedPrice = ($avgPrice * 0.6) + ($medianPrice * 0.4);
        
        // Determine confidence level
        $priceVariance = $prices->map(fn($price) => abs($price - $avgPrice))->avg();
        $confidence = $priceVariance < ($avgPrice * 0.2) ? 'high' : 
                     ($priceVariance < ($avgPrice * 0.4) ? 'medium' : 'low');

        $analysis = $this->generatePriceAnalysis($currentPrice, $suggestedPrice, $avgPrice, $medianPrice);

        return [
            'current_price' => $currentPrice,
            'suggested_price' => round($suggestedPrice, 2),
            'market_average' => round($avgPrice, 2),
            'market_median' => round($medianPrice, 2),
            'confidence' => $confidence,
            'price_variance' => round($priceVariance, 2),
            'market_analysis' => $analysis,
            'sample_size' => $similarProperties->count()
        ];
    }

    /**
     * Get optimal pricing calendar for a property
     */
    public function getOptimalPricingCalendar(Property $property, int $days = 30): array
    {
        $basePrice = $property->price_per_night;
        $calendar = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = now()->addDays($i);
            $multiplier = $this->calculatePriceMultiplier($date, $property);
            
            $calendar[] = [
                'date' => $date->format('Y-m-d'),
                'base_price' => $basePrice,
                'suggested_price' => round($basePrice * $multiplier, 2),
                'multiplier' => $multiplier,
                'demand_level' => $this->getDemandLevel($multiplier),
                'day_type' => $this->getDayType($date)
            ];
        }
        
        return $calendar;
    }

    /**
     * Build user profile for recommendations
     */
    private function buildUserProfile(User $user): array
    {
        $bookings = $user->bookings()->with('property')->get();
        $reviews = $user->reviewsAsGuest()->with('property')->get();
        $savedSearches = $user->savedSearches()->get();

        // Analyze booking patterns
        $preferredPriceRange = $this->analyzePreferredPriceRange($bookings);
        $preferredLocations = $this->analyzePreferredLocations($bookings);
        $preferredAmenities = $this->analyzePreferredAmenities($bookings, $reviews);
        $bookingPatterns = $this->analyzeBookingPatterns($bookings);

        return [
            'price_range' => $preferredPriceRange,
            'preferred_locations' => $preferredLocations,
            'preferred_amenities' => $preferredAmenities,
            'booking_patterns' => $bookingPatterns,
            'saved_search_criteria' => $this->analyzeSavedSearches($savedSearches),
            'total_bookings' => $bookings->count(),
            'average_rating_given' => $reviews->avg('rating') ?? 5.0,
        ];
    }

    /**
     * Calculate recommendation score for a property
     */
    private function calculateRecommendationScore(Property $property, array $userProfile): float
    {
        $scores = [];

        // Location similarity
        $scores['location'] = $this->calculateLocationScore($property, $userProfile['preferred_locations']);

        // Price compatibility
        $scores['price'] = $this->calculatePriceScore($property, $userProfile['price_range']);

        // Amenity match
        $scores['amenities'] = $this->calculateAmenityScore($property, $userProfile['preferred_amenities']);

        // Review score
        $scores['reviews'] = $this->calculateReviewScore($property);

        // Booking history alignment
        $scores['booking_history'] = $this->calculateBookingHistoryScore($property, $userProfile['booking_patterns']);

        // User preferences from saved searches
        $scores['preferences'] = $this->calculatePreferenceScore($property, $userProfile['saved_search_criteria']);

        // Calculate weighted total
        $totalScore = 0;
        foreach ($this->weights as $category => $weight) {
            $scoreKey = str_replace('_similarity', '', str_replace('_compatibility', '', str_replace('_match', '', str_replace('_score', '', $category))));
            $totalScore += ($scores[$scoreKey] ?? 0) * $weight;
        }

        return round($totalScore, 3);
    }

    /**
     * Calculate similarity score between two properties
     */
    private function calculateSimilarityScore(Property $property1, Property $property2): float
    {
        $scores = [];

        // Location proximity
        $scores['location'] = $this->calculateLocationProximity($property1, $property2);

        // Price similarity
        $scores['price'] = $this->calculatePriceSimilarity($property1, $property2);

        // Amenity overlap
        $scores['amenities'] = $this->calculateAmenityOverlap($property1, $property2);

        // Property type similarity
        $scores['type'] = $property1->property_type === $property2->property_type ? 1.0 : 0.5;

        // Size similarity
        $scores['size'] = $this->calculateSizeSimilarity($property1, $property2);

        // Average all scores
        return array_sum($scores) / count($scores);
    }

    /**
     * Calculate location score based on user preferences
     */
    private function calculateLocationScore(Property $property, array $preferredLocations): float
    {
        if (empty($preferredLocations)) {
            return 0.5; // Neutral score if no location data
        }

        $maxScore = 0;
        foreach ($preferredLocations as $location => $frequency) {
            $similarity = $this->calculateLocationSimilarity($property->location, $location);
            $score = $similarity * ($frequency / 100); // Normalize frequency
            $maxScore = max($maxScore, $score);
        }

        return $maxScore;
    }

    /**
     * Calculate price compatibility score
     */
    private function calculatePriceScore(Property $property, array $priceRange): float
    {
        if (empty($priceRange)) {
            return 0.5;
        }

        $propertyPrice = $property->price_per_night;
        $minPrice = $priceRange['min'] ?? 0;
        $maxPrice = $priceRange['max'] ?? PHP_INT_MAX;
        $preferredPrice = $priceRange['preferred'] ?? ($minPrice + $maxPrice) / 2;

        if ($propertyPrice >= $minPrice && $propertyPrice <= $maxPrice) {
            // Within range, calculate how close to preferred
            $distance = abs($propertyPrice - $preferredPrice);
            $range = $maxPrice - $minPrice;
            return max(0, 1 - ($distance / $range));
        }

        return 0.1; // Outside preferred range
    }

    /**
     * Calculate amenity matching score
     */
    private function calculateAmenityScore(Property $property, array $preferredAmenities): float
    {
        if (empty($preferredAmenities)) {
            return 0.5;
        }

        $propertyAmenities = json_decode($property->amenities ?? '[]', true);
        $matchScore = 0;
        $totalWeight = 0;

        foreach ($preferredAmenities as $amenity => $importance) {
            $totalWeight += $importance;
            if (in_array($amenity, $propertyAmenities)) {
                $matchScore += $importance;
            }
        }

        return $totalWeight > 0 ? $matchScore / $totalWeight : 0.5;
    }

    /**
     * Calculate review-based score
     */
    private function calculateReviewScore(Property $property): float
    {
        $avgRating = $property->reviews()->avg('rating') ?? 3.0;
        $reviewCount = $property->reviews()->count();
        
        // Normalize rating (0-5 scale to 0-1 scale)
        $ratingScore = $avgRating / 5;
        
        // Apply confidence factor based on number of reviews
        $confidenceFactor = min(1, $reviewCount / 10); // Full confidence at 10+ reviews
        
        return $ratingScore * (0.5 + 0.5 * $confidenceFactor);
    }

    /**
     * Additional helper methods for the AI recommendation system
     */
    private function analyzePreferredPriceRange($bookings): array
    {
        if ($bookings->isEmpty()) {
            return [];
        }

        $prices = $bookings->pluck('total_price');
        return [
            'min' => $prices->min(),
            'max' => $prices->max(),
            'preferred' => $prices->median(),
            'average' => $prices->avg()
        ];
    }

    private function analyzePreferredLocations($bookings): array
    {
        if ($bookings->isEmpty()) {
            return [];
        }

        return $bookings->groupBy('property.location')
            ->map->count()
            ->sortDesc()
            ->take(5)
            ->toArray();
    }

    private function analyzePreferredAmenities($bookings, $reviews): array
    {
        $amenityPreferences = [];
        
        foreach ($bookings as $booking) {
            $amenities = json_decode($booking->property->amenities ?? '[]', true);
            foreach ($amenities as $amenity) {
                $amenityPreferences[$amenity] = ($amenityPreferences[$amenity] ?? 0) + 1;
            }
        }

        // Boost amenities mentioned positively in reviews
        foreach ($reviews as $review) {
            if ($review->rating >= 4 && $review->comment) {
                // Simple keyword matching for amenities
                $comment = strtolower($review->comment);
                $amenityKeywords = ['wifi', 'parking', 'pool', 'gym', 'kitchen', 'balcony'];
                foreach ($amenityKeywords as $keyword) {
                    if (strpos($comment, $keyword) !== false) {
                        $amenityPreferences[$keyword] = ($amenityPreferences[$keyword] ?? 0) + 2;
                    }
                }
            }
        }

        return $amenityPreferences;
    }

    private function analyzeBookingPatterns($bookings): array
    {
        if ($bookings->isEmpty()) {
            return [];
        }

        $patterns = [];
        
        // Duration preferences
        $durations = $bookings->map(function ($booking) {
            return $booking->start_date->diffInDays($booking->end_date);
        });
        
        $patterns['preferred_duration'] = $durations->median();
        $patterns['guest_count'] = $bookings->avg('guest_count');
        
        // Seasonal preferences
        $months = $bookings->map(function ($booking) {
            return $booking->start_date->format('n');
        })->countBy()->sortDesc();
        
        $patterns['preferred_months'] = $months->take(3)->keys()->toArray();
        
        return $patterns;
    }

    private function analyzeSavedSearches($savedSearches): array
    {
        $criteria = [];
        
        foreach ($savedSearches as $search) {
            $searchCriteria = json_decode($search->search_criteria, true);
            foreach ($searchCriteria as $key => $value) {
                if (!isset($criteria[$key])) {
                    $criteria[$key] = [];
                }
                $criteria[$key][] = $value;
            }
        }

        return $criteria;
    }

    private function calculateLocationProximity($property1, $property2): float
    {
        // Simple string similarity for now
        // In production, you'd use actual geolocation data
        return $property1->location === $property2->location ? 1.0 : 
               (stripos($property1->location, $property2->location) !== false ? 0.7 : 0.2);
    }

    private function calculatePriceSimilarity($property1, $property2): float
    {
        $price1 = $property1->price_per_night;
        $price2 = $property2->price_per_night;
        $difference = abs($price1 - $price2);
        $average = ($price1 + $price2) / 2;
        
        return max(0, 1 - ($difference / $average));
    }

    private function calculateAmenityOverlap($property1, $property2): float
    {
        $amenities1 = json_decode($property1->amenities ?? '[]', true);
        $amenities2 = json_decode($property2->amenities ?? '[]', true);
        
        if (empty($amenities1) && empty($amenities2)) {
            return 1.0;
        }
        
        $intersection = array_intersect($amenities1, $amenities2);
        $union = array_unique(array_merge($amenities1, $amenities2));
        
        return count($union) > 0 ? count($intersection) / count($union) : 0;
    }

    private function calculateSizeSimilarity($property1, $property2): float
    {
        $bedrooms1 = $property1->bedrooms ?? 1;
        $bedrooms2 = $property2->bedrooms ?? 1;
        $bathrooms1 = $property1->bathrooms ?? 1;
        $bathrooms2 = $property2->bathrooms ?? 1;
        
        $bedroomSimilarity = 1 - (abs($bedrooms1 - $bedrooms2) / max($bedrooms1, $bedrooms2, 1));
        $bathroomSimilarity = 1 - (abs($bathrooms1 - $bathrooms2) / max($bathrooms1, $bathrooms2, 1));
        
        return ($bedroomSimilarity + $bathroomSimilarity) / 2;
    }

    private function findPropertiesMatchingSavedSearch($search, $limit): Collection
    {
        $criteria = json_decode($search->search_criteria, true);
        $query = Property::where('is_active', true);

        // Apply search criteria
        if (isset($criteria['location'])) {
            $query->where('location', 'like', '%' . $criteria['location'] . '%');
        }
        
        if (isset($criteria['min_price'])) {
            $query->where('price_per_night', '>=', $criteria['min_price']);
        }
        
        if (isset($criteria['max_price'])) {
            $query->where('price_per_night', '<=', $criteria['max_price']);
        }
        
        if (isset($criteria['property_type'])) {
            $query->where('property_type', $criteria['property_type']);
        }

        return $query->with(['reviews', 'user', 'images'])
                    ->limit($limit)
                    ->get();
    }

    private function calculatePriceMultiplier($date, $property): float
    {
        $baseMultiplier = 1.0;
        
        // Weekend pricing
        if ($date->isWeekend()) {
            $baseMultiplier *= 1.2;
        }
        
        // Holiday pricing
        if ($this->isHoliday($date)) {
            $baseMultiplier *= 1.5;
        }
        
        // Seasonal pricing
        $month = $date->month;
        if (in_array($month, [6, 7, 8, 12])) { // Summer and December
            $baseMultiplier *= 1.3;
        }
        
        // Local demand (simplified)
        $localDemand = $this->calculateLocalDemand($date, $property);
        $baseMultiplier *= (1 + ($localDemand * 0.3));
        
        return round($baseMultiplier, 2);
    }

    private function isHoliday($date): bool
    {
        // Simplified holiday detection
        $holidays = [
            '01-01', // New Year
            '12-25', // Christmas
            '07-04', // Independence Day (US)
            '11-28', // Thanksgiving (approximate)
        ];
        
        return in_array($date->format('m-d'), $holidays);
    }

    private function calculateLocalDemand($date, $property): float
    {
        // Simplified demand calculation based on recent bookings
        $recentBookings = Booking::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->whereHas('property', function ($query) use ($property) {
                $query->where('location', 'like', '%' . $property->location . '%');
            })
            ->count();
            
        return min(1.0, $recentBookings / 10); // Normalize to 0-1
    }

    private function getDemandLevel($multiplier): string
    {
        if ($multiplier >= 1.4) return 'high';
        if ($multiplier >= 1.2) return 'medium';
        return 'low';
    }

    private function getDayType($date): string
    {
        if ($date->isWeekend()) return 'weekend';
        if ($this->isHoliday($date)) return 'holiday';
        return 'weekday';
    }

    private function generatePriceAnalysis($current, $suggested, $average, $median): string
    {
        $difference = $suggested - $current;
        $percentDiff = $current > 0 ? ($difference / $current) * 100 : 0;
        
        if (abs($percentDiff) < 5) {
            return "Your pricing is well-aligned with the market.";
        } elseif ($percentDiff > 10) {
            return "Consider increasing your price by " . round($percentDiff) . "% to match market rates.";
        } elseif ($percentDiff < -10) {
            return "Your price is above market average. Consider reducing by " . round(abs($percentDiff)) . "% for better competitiveness.";
        } else {
            return "Minor price adjustment of " . round(abs($percentDiff)) . "% " . 
                   ($percentDiff > 0 ? "increase" : "decrease") . " recommended.";
        }
    }

    private function calculateLocationSimilarity($location1, $location2): float
    {
        // Simple implementation - in production use proper geo-distance calculation
        if ($location1 === $location2) return 1.0;
        
        $words1 = explode(' ', strtolower($location1));
        $words2 = explode(' ', strtolower($location2));
        $intersection = array_intersect($words1, $words2);
        $union = array_unique(array_merge($words1, $words2));
        
        return count($union) > 0 ? count($intersection) / count($union) : 0;
    }

    private function calculateBookingHistoryScore($property, $patterns): float
    {
        if (empty($patterns)) return 0.5;
        
        $score = 0.5; // Base score
        
        // Match preferred duration
        if (isset($patterns['preferred_duration'])) {
            // Properties that typically accommodate the user's preferred stay duration
            $score += 0.2; // Simplified scoring
        }
        
        // Match guest capacity
        if (isset($patterns['guest_count']) && $property->guest_capacity) {
            $guestCompatibility = 1 - abs($patterns['guest_count'] - $property->guest_capacity) / max($patterns['guest_count'], $property->guest_capacity, 1);
            $score = ($score + $guestCompatibility) / 2;
        }
        
        return min(1.0, $score);
    }

    private function calculatePreferenceScore($property, $savedSearchCriteria): float
    {
        if (empty($savedSearchCriteria)) return 0.5;
        
        $score = 0;
        $criteriaCount = 0;
        
        foreach ($savedSearchCriteria as $criterion => $values) {
            $criteriaCount++;
            switch ($criterion) {
                case 'location':
                    foreach ($values as $value) {
                        if (stripos($property->location, $value) !== false) {
                            $score += 1;
                            break;
                        }
                    }
                    break;
                case 'property_type':
                    if (in_array($property->property_type, $values)) {
                        $score += 1;
                    }
                    break;
                case 'min_price':
                case 'max_price':
                    // Price criteria handled in price score
                    $score += 0.5;
                    break;
            }
        }
        
        return $criteriaCount > 0 ? $score / $criteriaCount : 0.5;
    }
}
