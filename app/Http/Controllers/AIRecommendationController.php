<?php

namespace App\Http\Controllers;

use App\Services\AIRecommendationService;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIRecommendationController extends Controller
{
    protected $aiService;

    public function __construct(AIRecommendationService $aiService)
    {
        $this->middleware('auth');
        $this->aiService = $aiService;
    }

    /**
     * Get personalized recommendations for the current user
     */
    public function getPersonalizedRecommendations(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        
        $recommendations = $this->aiService->getPersonalizedRecommendations($user, $limit);
        
        if ($request->expectsJson()) {
            return response()->json([
                'recommendations' => $recommendations,
                'user_profile' => $this->getUserProfile($user)
            ]);
        }
        
        return view('recommendations.personalized', compact('recommendations'));
    }

    /**
     * Get similar properties for a given property
     */
    public function getSimilarProperties(Property $property, Request $request)
    {
        $limit = $request->get('limit', 8);
        
        $similarProperties = $this->aiService->getSimilarProperties($property, $limit);
        
        if ($request->expectsJson()) {
            return response()->json(['similar_properties' => $similarProperties]);
        }
        
        return view('recommendations.similar', compact('property', 'similarProperties'));
    }

    /**
     * Get trending properties
     */
    public function getTrendingProperties(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $trendingProperties = $this->aiService->getTrendingProperties($limit);
        
        if ($request->expectsJson()) {
            return response()->json(['trending_properties' => $trendingProperties]);
        }
        
        return view('recommendations.trending', compact('trendingProperties'));
    }

    /**
     * Get recommendations based on saved searches
     */
    public function getRecommendationsFromSavedSearches(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 6);
        
        $recommendations = $this->aiService->getRecommendationsFromSavedSearches($user, $limit);
        
        return response()->json(['recommendations' => $recommendations]);
    }

    /**
     * Get price prediction for a property
     */
    public function getPricePrediction(Property $property, Request $request)
    {
        $this->authorize('update', $property);
        
        $prediction = $this->aiService->getPricePrediction($property);
        
        if ($request->expectsJson()) {
            return response()->json($prediction);
        }
        
        return view('properties.price-prediction', compact('property', 'prediction'));
    }

    /**
     * Get optimal pricing calendar
     */
    public function getOptimalPricingCalendar(Property $property, Request $request)
    {
        $this->authorize('update', $property);
        
        $days = $request->get('days', 30);
        $calendar = $this->aiService->getOptimalPricingCalendar($property, $days);
        
        return response()->json(['calendar' => $calendar]);
    }

    /**
     * Update recommendation preferences
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'preferences' => 'required|array',
            'preferences.price_sensitivity' => 'required|numeric|between:0,1',
            'preferences.location_importance' => 'required|numeric|between:0,1',
            'preferences.amenity_importance' => 'required|numeric|between:0,1',
            'preferences.review_importance' => 'required|numeric|between:0,1',
        ]);

        $user = Auth::user();
        
        // Store preferences in user profile or separate table
        $user->update([
            'recommendation_preferences' => $request->preferences
        ]);

        return response()->json(['message' => 'Preferences updated successfully']);
    }

    /**
     * Get recommendation insights and explanations
     */
    public function getRecommendationInsights(Request $request)
    {
        $user = Auth::user();
        $propertyId = $request->get('property_id');
        
        $insights = [
            'user_booking_patterns' => $this->getUserBookingPatterns($user),
            'price_analysis' => $this->getPriceAnalysis($user),
            'location_preferences' => $this->getLocationPreferences($user),
            'amenity_preferences' => $this->getAmenityPreferences($user)
        ];

        if ($propertyId) {
            $property = Property::findOrFail($propertyId);
            $insights['property_match_score'] = $this->getPropertyMatchScore($user, $property);
        }

        return response()->json($insights);
    }

    /**
     * Provide feedback on recommendations
     */
    public function provideFeedback(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'feedback_type' => 'required|in:liked,disliked,viewed,booked,not_interested',
            'rating' => 'nullable|numeric|between:1,5',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        
        // Store feedback for improving recommendations
        \DB::table('recommendation_feedback')->insert([
            'user_id' => $user->id,
            'property_id' => $request->property_id,
            'feedback_type' => $request->feedback_type,
            'rating' => $request->rating,
            'notes' => $request->notes,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Feedback recorded successfully']);
    }

    /**
     * Get AI-powered search suggestions
     */
    public function getSearchSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        $user = Auth::user();
        
        $suggestions = [
            'locations' => $this->getLocationSuggestions($query, $user),
            'property_types' => $this->getPropertyTypeSuggestions($query),
            'amenities' => $this->getAmenitySuggestions($query),
            'price_ranges' => $this->getPriceRangeSuggestions($user),
            'popular_searches' => $this->getPopularSearches()
        ];

        return response()->json($suggestions);
    }

    /**
     * Helper methods
     */
    private function getUserProfile(User $user): array
    {
        return [
            'total_bookings' => $user->bookings()->count(),
            'preferred_price_range' => $this->getPreferredPriceRange($user),
            'favorite_locations' => $this->getFavoriteLocations($user),
            'preferred_amenities' => $this->getPreferredAmenities($user),
            'booking_frequency' => $this->getBookingFrequency($user)
        ];
    }

    private function getUserBookingPatterns(User $user): array
    {
        $bookings = $user->bookings()->with('property')->get();
        
        return [
            'average_stay_duration' => $bookings->avg(function ($booking) {
                return $booking->start_date->diffInDays($booking->end_date);
            }),
            'preferred_seasons' => $this->getPreferredSeasons($bookings),
            'booking_lead_time' => $this->getAverageLeadTime($bookings),
            'guest_count_pattern' => $bookings->avg('guest_count')
        ];
    }

    private function getPriceAnalysis(User $user): array
    {
        $bookings = $user->bookings()->get();
        
        if ($bookings->isEmpty()) {
            return ['message' => 'No booking history available'];
        }

        $prices = $bookings->pluck('total_price');
        
        return [
            'average_booking_value' => $prices->avg(),
            'price_range' => [
                'min' => $prices->min(),
                'max' => $prices->max()
            ],
            'price_trend' => $this->calculatePriceTrend($bookings)
        ];
    }

    private function getLocationPreferences(User $user): array
    {
        $bookings = $user->bookings()->with('property')->get();
        
        $locationCounts = $bookings->groupBy('property.location')
            ->map->count()
            ->sortDesc()
            ->take(5);

        return [
            'favorite_locations' => $locationCounts->keys()->toArray(),
            'location_diversity' => $locationCounts->count(),
            'repeat_location_rate' => $locationCounts->values()->first() / $bookings->count() * 100
        ];
    }

    private function getAmenityPreferences(User $user): array
    {
        $bookings = $user->bookings()->with('property')->get();
        $amenityCount = [];

        foreach ($bookings as $booking) {
            $amenities = json_decode($booking->property->amenities ?? '[]', true);
            foreach ($amenities as $amenity) {
                $amenityCount[$amenity] = ($amenityCount[$amenity] ?? 0) + 1;
            }
        }

        arsort($amenityCount);

        return [
            'most_preferred' => array_slice($amenityCount, 0, 10, true),
            'amenity_importance_score' => $this->calculateAmenityImportance($amenityCount, $bookings->count())
        ];
    }

    private function getPropertyMatchScore(User $user, Property $property): array
    {
        // This would use the AIRecommendationService to calculate detailed match scores
        return [
            'overall_score' => rand(70, 95), // Placeholder
            'price_match' => rand(60, 100),
            'location_match' => rand(70, 95),
            'amenity_match' => rand(80, 100),
            'size_match' => rand(75, 90),
            'explanation' => 'Based on your booking history and preferences'
        ];
    }

    private function getLocationSuggestions(string $query, User $user): array
    {
        // Get locations from user's booking history and popular locations
        return Property::where('location', 'like', "%{$query}%")
            ->distinct()
            ->pluck('location')
            ->take(5)
            ->toArray();
    }

    private function getPropertyTypeSuggestions(string $query): array
    {
        return Property::where('property_type', 'like', "%{$query}%")
            ->distinct()
            ->pluck('property_type')
            ->take(5)
            ->toArray();
    }

    private function getAmenitySuggestions(string $query): array
    {
        // This would typically query a separate amenities table
        $commonAmenities = [
            'WiFi', 'Kitchen', 'Parking', 'Pool', 'Gym', 'Balcony', 
            'Air Conditioning', 'Heating', 'TV', 'Washer', 'Dryer'
        ];

        return array_filter($commonAmenities, function ($amenity) use ($query) {
            return stripos($amenity, $query) !== false;
        });
    }

    private function getPriceRangeSuggestions(User $user): array
    {
        $userHistory = $user->bookings()->avg('total_price') ?? 100;
        
        return [
            ['label' => 'Budget', 'min' => 0, 'max' => $userHistory * 0.7],
            ['label' => 'Your Range', 'min' => $userHistory * 0.8, 'max' => $userHistory * 1.2],
            ['label' => 'Premium', 'min' => $userHistory * 1.3, 'max' => null]
        ];
    }

    private function getPopularSearches(): array
    {
        // This would typically come from search analytics
        return [
            'beach house', 'city apartment', 'mountain cabin', 
            'luxury villa', 'family friendly', 'pet friendly'
        ];
    }

    // Additional helper methods would go here...
    private function getPreferredPriceRange(User $user): array
    {
        $bookings = $user->bookings()->get();
        if ($bookings->isEmpty()) {
            return ['min' => 0, 'max' => 200];
        }
        
        $prices = $bookings->pluck('total_price');
        return [
            'min' => $prices->min(),
            'max' => $prices->max(),
            'average' => $prices->avg()
        ];
    }

    private function getFavoriteLocations(User $user): array
    {
        return $user->bookings()
            ->with('property')
            ->get()
            ->groupBy('property.location')
            ->map->count()
            ->sortDesc()
            ->take(3)
            ->keys()
            ->toArray();
    }

    private function getPreferredAmenities(User $user): array
    {
        // Similar implementation to getAmenityPreferences but simplified
        return ['WiFi', 'Kitchen', 'Parking']; // Placeholder
    }

    private function getBookingFrequency(User $user): string
    {
        $bookingCount = $user->bookings()->count();
        $accountAge = $user->created_at->diffInMonths(now());
        
        if ($accountAge === 0) return 'New User';
        
        $frequency = $bookingCount / $accountAge;
        
        if ($frequency >= 2) return 'Frequent';
        if ($frequency >= 1) return 'Regular';
        if ($frequency >= 0.5) return 'Occasional';
        return 'Rare';
    }

    private function getPreferredSeasons($bookings): array
    {
        $seasons = $bookings->groupBy(function ($booking) {
            $month = $booking->start_date->month;
            if (in_array($month, [12, 1, 2])) return 'Winter';
            if (in_array($month, [3, 4, 5])) return 'Spring';
            if (in_array($month, [6, 7, 8])) return 'Summer';
            return 'Fall';
        })->map->count()->sortDesc();

        return $seasons->toArray();
    }

    private function getAverageLeadTime($bookings): float
    {
        if ($bookings->isEmpty()) return 0;
        
        return $bookings->avg(function ($booking) {
            return $booking->created_at->diffInDays($booking->start_date);
        });
    }

    private function calculatePriceTrend($bookings): string
    {
        if ($bookings->count() < 2) return 'Insufficient data';
        
        $recent = $bookings->sortByDesc('created_at')->take(3)->avg('total_price');
        $older = $bookings->sortBy('created_at')->take(3)->avg('total_price');
        
        $change = (($recent - $older) / $older) * 100;
        
        if ($change > 10) return 'Increasing';
        if ($change < -10) return 'Decreasing';
        return 'Stable';
    }

    private function calculateAmenityImportance($amenityCount, $totalBookings): array
    {
        $importance = [];
        foreach ($amenityCount as $amenity => $count) {
            $importance[$amenity] = ($count / $totalBookings) * 100;
        }
        return $importance;
    }
}
