<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class TestRecommendationAlgorithms extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:recommendations';

    /**
     * The console command description.
     */
    protected $description = 'Test AI recommendation algorithms with real data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting AI Recommendation Algorithm Testing...');
        $this->newLine();

        // Check data availability
        $this->checkDataAvailability();
        
        // Test 1: Basic Recommendation Engine
        $this->testBasicRecommendations();
        
        // Test 2: User Preference Analysis
        $this->testUserPreferences();
        
        // Test 3: Property Similarity Analysis
        $this->testPropertySimilarity();
        
        // Test 4: Collaborative Filtering
        $this->testCollaborativeFiltering();
        
        // Test 5: Performance Metrics
        $this->calculatePerformanceMetrics();
        
        $this->newLine();
        $this->info('✅ AI Recommendation Algorithm Testing Completed!');
        
        return 0;
    }

    private function checkDataAvailability()
    {
        $this->info('📊 Checking Data Availability...');
        
        $users = User::count();
        $properties = Property::count();
        $bookings = Booking::count();
        $reviews = Review::count();
        $feedback = DB::table('recommendation_feedback')->count();
        
        $this->table(['Data Type', 'Count', 'Status'], [
            ['Users', $users, $users >= 5 ? '✅ Good' : '⚠️ Low'],
            ['Properties', $properties, $properties >= 20 ? '✅ Good' : '⚠️ Low'],
            ['Bookings', $bookings, $bookings >= 10 ? '✅ Good' : '⚠️ Low'],
            ['Reviews', $reviews, $reviews >= 5 ? '✅ Good' : '⚠️ Low'],
            ['Feedback Records', $feedback, $feedback >= 50 ? '✅ Good' : '⚠️ Low']
        ]);
        
        if ($users < 5 || $properties < 10) {
            $this->warn('⚠️ Insufficient data for comprehensive testing. Results may be limited.');
        }
        
        $this->newLine();
    }

    private function testBasicRecommendations()
    {
        $this->info('🎯 Test 1: Basic Recommendation Engine');
        
        // Get a sample user
        $testUser = User::role('renter')->first();
        if (!$testUser) {
            $this->error('No renter users found for testing.');
            return;
        }

        // Get properties for recommendations
        $properties = Property::where('is_active', true)->limit(10)->get();
        
        if ($properties->count() < 5) {
            $this->warn('Limited properties available for recommendations.');
        }

        // Simulate recommendation scoring
        $recommendations = $properties->map(function($property) use ($testUser) {
            return [
                'property_id' => $property->id,
                'title' => $property->title,
                'location' => $property->location,
                'price' => $property->price_per_night,
                'score' => $this->calculateBasicScore($property),
                'match_factors' => $this->getMatchFactors($property, $testUser)
            ];
        })->sortByDesc('score')->take(5);

        $this->table(['Property', 'Location', 'Price', 'Score', 'Match Factors'], 
            $recommendations->map(function($rec) {
                return [
                    substr($rec['title'], 0, 30) . '...',
                    substr($rec['location'], 0, 20),
                    '₱' . number_format($rec['price']),
                    round($rec['score'], 2),
                    implode(', ', $rec['match_factors'])
                ];
            })->toArray()
        );
        
        $avgScore = $recommendations->avg('score');
        $this->info("Average recommendation score: " . round($avgScore, 2) . "/10");
        $this->newLine();
    }

    private function testUserPreferences()
    {
        $this->info('👤 Test 2: User Preference Analysis');
        
        $users = User::role('renter')->limit(3)->get();
        
        foreach ($users as $user) {
            $this->line("Analyzing user: {$user->name}");
            
            // Get user's booking history
            $bookings = $user->bookings()->with('property')->get();
            
            if ($bookings->count() > 0) {
                $avgPrice = $bookings->avg('property.price_per_night');
                $commonLocations = $bookings->pluck('property.location')->countBy()->keys()->take(2);
                $preferredTypes = $bookings->pluck('property.property_type')->countBy();
                
                $this->line("  - Average price preference: ₱" . number_format($avgPrice));
                $this->line("  - Common locations: " . $commonLocations->implode(', '));
                $this->line("  - Preferred property types: " . $preferredTypes->keys()->implode(', '));
            } else {
                $this->line("  - No booking history available");
            }
            
            // Get recommendation preferences
            $preferences = $user->recommendation_preferences;
            if ($preferences) {
                $this->line("  - Price sensitivity: " . ($preferences['price_sensitivity'] ?? 'N/A'));
                $this->line("  - Location importance: " . ($preferences['location_importance'] ?? 'N/A'));
            }
        }
        
        $this->newLine();
    }

    private function testPropertySimilarity()
    {
        $this->info('🏠 Test 3: Property Similarity Analysis');
        
        $baseProperty = Property::with('reviews')->first();
        if (!$baseProperty) {
            $this->error('No properties found for similarity testing.');
            return;
        }

        $this->line("Base property: {$baseProperty->title}");
        $this->line("Type: {$baseProperty->property_type}");
        $this->line("Location: {$baseProperty->location}");
        $this->line("Price: ₱" . number_format($baseProperty->price_per_night));
        
        // Find similar properties
        $similarProperties = Property::where('id', '!=', $baseProperty->id)
            ->where('property_type', $baseProperty->property_type)
            ->orWhere('location', 'like', '%' . explode(',', $baseProperty->location)[0] . '%')
            ->limit(5)
            ->get();

        if ($similarProperties->count() > 0) {
            $this->line("\nSimilar properties found:");
            foreach ($similarProperties as $property) {
                $similarity = $this->calculateSimilarity($baseProperty, $property);
                $this->line("  - {$property->title} (Similarity: {$similarity}%)");
            }
        } else {
            $this->warn('No similar properties found.');
        }
        
        $this->newLine();
    }

    private function testCollaborativeFiltering()
    {
        $this->info('🤝 Test 4: Collaborative Filtering');
        
        $testUser = User::role('renter')->whereHas('bookings')->first();
        if (!$testUser) {
            $this->warn('No users with booking history found for collaborative filtering test.');
            return;
        }

        $this->line("Test user: {$testUser->name}");
        
        // Get user's booked properties
        $userProperties = $testUser->bookings()->pluck('property_id')->toArray();
        $this->line("User has booked " . count($userProperties) . " properties");
        
        // Find users who booked similar properties
        $similarUsers = User::role('renter')
            ->whereHas('bookings', function($query) use ($userProperties) {
                $query->whereIn('property_id', $userProperties);
            })
            ->where('id', '!=', $testUser->id)
            ->limit(5)
            ->get();

        $this->line("Found " . $similarUsers->count() . " users with similar booking patterns");
        
        // Get recommendations from similar users
        $recommendations = [];
        foreach ($similarUsers as $user) {
            $userRecommendations = $user->bookings()
                ->whereNotIn('property_id', $userProperties)
                ->with('property')
                ->limit(2)
                ->get();
                
            foreach ($userRecommendations as $booking) {
                $recommendations[] = $booking->property->title;
            }
        }
        
        if (count($recommendations) > 0) {
            $this->line("Collaborative recommendations: " . implode(', ', array_unique($recommendations)));
        } else {
            $this->line('No collaborative recommendations generated.');
        }
        
        $this->newLine();
    }

    private function calculatePerformanceMetrics()
    {
        $this->info('📈 Test 5: Performance Metrics');
        
        $totalUsers = User::role('renter')->count();
        $totalProperties = Property::count();
        $totalBookings = Booking::count();
        $totalFeedback = DB::table('recommendation_feedback')->count();
        
        // Calculate metrics
        $activeUsers = User::role('renter')->whereHas('bookings')->count();
        $userEngagement = $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0;
        
        $positiveFeedback = DB::table('recommendation_feedback')
            ->whereIn('feedback_type', ['clicked', 'saved', 'booked'])
            ->count();
        $accuracy = $totalFeedback > 0 ? ($positiveFeedback / $totalFeedback) * 100 : 0;
        
        $coverage = $totalProperties > 0 ? min(100, ($totalFeedback / $totalProperties) * 100) : 0;
        
        $avgRating = Review::avg('rating') ?? 0;
        $dataQuality = min(100, ($avgRating / 5) * 100);
        
        $this->table(['Metric', 'Value', 'Status'], [
            ['User Engagement Rate', round($userEngagement, 1) . '%', $userEngagement > 50 ? '✅ Good' : '⚠️ Needs Improvement'],
            ['Recommendation Accuracy', round($accuracy, 1) . '%', $accuracy > 60 ? '✅ Good' : '⚠️ Needs Improvement'],
            ['Algorithm Coverage', round($coverage, 1) . '%', $coverage > 70 ? '✅ Good' : '⚠️ Needs Improvement'],
            ['Data Quality Score', round($dataQuality, 1) . '%', $dataQuality > 75 ? '✅ Good' : '⚠️ Needs Improvement']
        ]);
        
        // Overall assessment
        $overallScore = ($userEngagement + $accuracy + $coverage + $dataQuality) / 4;
        
        if ($overallScore >= 75) {
            $this->info("🎉 Overall Performance: Excellent ({$overallScore}%)");
            $this->info("✅ Algorithms are ready for production deployment!");
        } elseif ($overallScore >= 60) {
            $this->info("👍 Overall Performance: Good ({$overallScore}%)");
            $this->info("⚠️ Some optimization recommended before full deployment.");
        } else {
            $this->warn("⚠️ Overall Performance: Needs Improvement ({$overallScore}%)");
            $this->warn("🔧 Significant optimization required before production deployment.");
        }
    }

    private function calculateBasicScore($property)
    {
        // Simple scoring algorithm based on multiple factors
        $score = 5; // Base score
        
        // Price factor (lower prices get higher scores)
        if ($property->price_per_night < 2000) $score += 2;
        elseif ($property->price_per_night < 4000) $score += 1;
        
        // Amenities factor
        $amenities = json_decode($property->amenities, true) ?? [];
        $score += min(2, count($amenities) * 0.2);
        
        // Reviews factor
        $avgRating = $property->reviews->avg('rating') ?? 3;
        $score += ($avgRating - 3) * 2; // Add 0-4 points based on ratings above 3
        
        // Location factor (simplified)
        if (stripos($property->location, 'Manila') !== false) $score += 1;
        if (stripos($property->location, 'Cebu') !== false) $score += 1;
        
        return min(10, max(0, $score));
    }

    private function getMatchFactors($property, $user)
    {
        $factors = [];
        
        if ($property->price_per_night < 3000) $factors[] = 'Affordable';
        if ($property->reviews->count() > 5) $factors[] = 'Well-reviewed';
        if (stripos($property->location, 'Manila') !== false) $factors[] = 'Prime location';
        
        $amenities = json_decode($property->amenities, true) ?? [];
        if (in_array('WiFi', $amenities)) $factors[] = 'WiFi';
        if (in_array('Pool', $amenities)) $factors[] = 'Pool';
        
        return $factors ?: ['Basic match'];
    }

    private function calculateSimilarity($property1, $property2)
    {
        $score = 0;
        
        // Type similarity
        if ($property1->property_type === $property2->property_type) $score += 40;
        
        // Location similarity
        $loc1 = explode(',', $property1->location)[0];
        $loc2 = explode(',', $property2->location)[0];
        if (stripos($loc2, $loc1) !== false || stripos($loc1, $loc2) !== false) $score += 30;
        
        // Price similarity (within 50% range)
        $priceDiff = abs($property1->price_per_night - $property2->price_per_night);
        $avgPrice = ($property1->price_per_night + $property2->price_per_night) / 2;
        if ($avgPrice > 0 && ($priceDiff / $avgPrice) < 0.5) $score += 20;
        
        // Size similarity
        if (abs($property1->max_guests - $property2->max_guests) <= 2) $score += 10;
        
        return min(100, $score);
    }
}
