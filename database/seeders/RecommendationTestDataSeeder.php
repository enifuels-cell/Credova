<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RecommendationTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding test data for AI recommendations...');

        // Create test users
        $this->createTestUsers();
        
        // Create test properties
        $this->createTestProperties();
        
        // Create test bookings
        $this->createTestBookings();
        
        // Create test reviews
        $this->createTestReviews();
        
        // Create recommendation feedback
        $this->createRecommendationFeedback();

        $this->command->info('Test data seeding completed!');
    }

    private function createTestUsers(): void
    {
        $this->command->info('Creating test users...');

        // Check if test users already exist
        $existingUsers = User::whereIn('email', [
            'john.doe@example.com',
            'jane.smith@example.com', 
            'bob.wilson@example.com',
            'alice.johnson@example.com',
            'charlie.brown@example.com'
        ])->count();

        if ($existingUsers > 0) {
            $this->command->info('Test users already exist, skipping user creation...');
            return;
        }

        // Ensure roles exist
        if (!Role::where('name', 'renter')->exists()) {
            Role::create(['name' => 'renter']);
        }
        if (!Role::where('name', 'landlord')->exists()) {
            Role::create(['name' => 'landlord']);
        }

        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password'),
                'recommendation_preferences' => [
                    'price_sensitivity' => 0.6,
                    'location_importance' => 0.8,
                    'amenity_importance' => 0.7,
                    'review_importance' => 0.9
                ],
                'role' => 'renter'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password'),
                'recommendation_preferences' => [
                    'price_sensitivity' => 0.4,
                    'location_importance' => 0.6,
                    'amenity_importance' => 0.9,
                    'review_importance' => 0.7
                ],
                'role' => 'renter'
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob.wilson@example.com',
                'password' => Hash::make('password'),
                'recommendation_preferences' => [
                    'price_sensitivity' => 0.8,
                    'location_importance' => 0.5,
                    'amenity_importance' => 0.4,
                    'review_importance' => 0.6
                ],
                'role' => 'renter'
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice.brown@example.com',
                'password' => Hash::make('password'),
                'recommendation_preferences' => [
                    'price_sensitivity' => 0.3,
                    'location_importance' => 0.9,
                    'amenity_importance' => 0.8,
                    'review_importance' => 0.8
                ],
                'role' => 'landlord'
            ],
            [
                'name' => 'Charlie Davis',
                'email' => 'charlie.davis@example.com',
                'password' => Hash::make('password'),
                'recommendation_preferences' => [
                    'price_sensitivity' => 0.7,
                    'location_importance' => 0.7,
                    'amenity_importance' => 0.6,
                    'review_importance' => 0.5
                ],
                'role' => 'landlord'
            ]
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::create($userData);
            $user->assignRole($role);
        }
    }

    private function createTestProperties(): void
    {
        $this->command->info('Creating test properties...');

        $locations = [
            'Manila, Metro Manila',
            'Cebu City, Cebu',
            'Davao City, Davao del Sur',
            'Baguio City, Benguet',
            'Boracay, Aklan',
            'El Nido, Palawan',
            'Bohol, Bohol',
            'Iloilo City, Iloilo',
            'Makati City, Metro Manila',
            'Taguig City, Metro Manila'
        ];

        $propertyTypes = ['apartment', 'house', 'villa', 'condo', 'studio', 'loft'];
        
        $amenitiesPool = [
            'WiFi', 'Kitchen', 'Parking', 'Pool', 'Gym', 'Balcony', 
            'Air Conditioning', 'Heating', 'TV', 'Washer', 'Dryer',
            'Hot Tub', 'Garden', 'BBQ Area', 'Security', 'Elevator'
        ];

        $owners = User::role('landlord')->get();

        for ($i = 1; $i <= 30; $i++) {
            $location = $locations[array_rand($locations)];
            $propertyType = $propertyTypes[array_rand($propertyTypes)];
            $maxGuests = rand(1, 8);
            $bedrooms = rand(1, min(4, $maxGuests));
            $bathrooms = rand(1, 3);
            
            // Select random amenities
            $amenityCount = rand(4, 8);
            $amenities = array_rand(array_flip($amenitiesPool), $amenityCount);
            if (!is_array($amenities)) $amenities = [$amenities];

            Property::create([
                'title' => "Beautiful {$propertyType} in " . explode(',', $location)[0],
                'description' => "A wonderful {$propertyType} perfect for your stay. Features modern amenities and great location access.",
                'location' => $location,
                'property_type' => $propertyType,
                'max_guests' => $maxGuests,
                'bedrooms' => $bedrooms,
                'bathrooms' => $bathrooms,
                'price_per_night' => rand(1000, 8000),
                'cleaning_fee' => rand(500, 2000),
                'amenities' => json_encode(array_values($amenities)),
                'house_rules' => 'No smoking, No pets, Check-in after 3 PM, Check-out before 11 AM',
                'cancellation_policy' => ['flexible', 'moderate', 'strict'][rand(0, 2)],
                'minimum_stay' => rand(1, 3),
                'instant_book' => rand(0, 1), // Use instant_book instead of instant_booking
                'is_active' => true, // Use is_active instead of status
                'user_id' => $owners->random()->id,
                'image' => "property-" . ($i % 10 + 1) . "-1.jpg", // Use single image instead of images array
                'lat' => rand(1400, 1600) / 100, // Use lat instead of latitude, proper Philippines coordinates
                'lng' => rand(12000, 12200) / 100, // Use lng instead of longitude
                'created_at' => now()->subDays(rand(1, 90)),
                'updated_at' => now()->subDays(rand(0, 30))
            ]);
        }
    }

    private function createTestBookings(): void
    {
        $this->command->info('Creating test bookings...');

        $users = User::role('renter')->get();
        $properties = Property::all();

        for ($i = 1; $i <= 50; $i++) {
            $user = $users->random();
            $property = $properties->random();
            
            $startDate = now()->addDays(rand(-30, 60));
            $endDate = $startDate->copy()->addDays(rand(1, 7));
            $nights = $startDate->diffInDays($endDate);
            
            $guestCount = rand(1, min(4, $property->max_guests));
            $totalPrice = ($property->price_per_night * $nights) + $property->cleaning_fee;

            Booking::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'guest_count' => $guestCount,
                'total_price' => $totalPrice,
                'status' => ['confirmed', 'pending', 'cancelled'][rand(0, 2)],
                'special_requests' => rand(0, 1) ? 'Late check-in requested' : null,
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(0, 30))
            ]);
        }
    }

    private function createTestReviews(): void
    {
        $this->command->info('Creating test reviews...');

        $completedBookings = Booking::where('status', 'confirmed')->get();
        
        $reviewTexts = [
            'Amazing place! Great location and very clean.',
            'Perfect for a family vacation. Host was very responsive.',
            'Beautiful property with all the amenities we needed.',
            'Would definitely stay here again. Highly recommended!',
            'Nice place but could use better WiFi.',
            'Excellent value for money. Clean and comfortable.',
            'Great location, close to everything we wanted to visit.',
            'Property was exactly as described. No surprises.',
            'Host went above and beyond to make us feel welcome.',
            'Clean, comfortable, and well-equipped. Perfect stay!'
        ];

        foreach ($completedBookings->take(30) as $booking) {
            $property = $booking->property;
            $guest = $booking->user;
            $host = $property->user;

            Review::create([
                'booking_id' => $booking->id,
                'property_id' => $property->id,
                'user_id' => $guest->id, // Use user_id instead of guest_id
                'host_id' => $host->id,
                'rating' => rand(3, 5), // Overall rating
                'cleanliness_rating' => rand(3, 5),
                'communication_rating' => rand(3, 5),
                'location_rating' => rand(3, 5),
                'value_rating' => rand(3, 5),
                'comment' => $reviewTexts[array_rand($reviewTexts)], // Use comment instead of review_text
                'is_public' => true,
                'is_verified' => rand(0, 1),
                'created_at' => $booking->end_date->addDays(rand(1, 5)),
                'updated_at' => now()
            ]);
        }
    }

    private function createRecommendationFeedback(): void
    {
        $this->command->info('Creating recommendation feedback...');

        $users = User::role('renter')->get();
        $properties = Property::all();

        for ($i = 1; $i <= 100; $i++) {
            \DB::table('recommendation_feedback')->insert([
                'user_id' => $users->random()->id,
                'property_id' => $properties->random()->id,
                'feedback_type' => ['liked', 'disliked', 'viewed', 'not_interested'][rand(0, 3)],
                'rating' => rand(0, 1) ? rand(10, 50) / 10 : null,
                'notes' => rand(0, 1) ? 'Generated test feedback' : null,
                'context' => json_encode([
                    'recommendation_source' => ['personalized', 'similar', 'trending'][rand(0, 2)],
                    'match_score' => rand(60, 95)
                ]),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 15))
            ]);
        }
    }
}
