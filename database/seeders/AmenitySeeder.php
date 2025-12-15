<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            // Basic Amenities
            ['name' => 'WiFi/Internet', 'icon' => 'wifi', 'category' => 'basic'],
            ['name' => 'Air Conditioning', 'icon' => 'snowflake', 'category' => 'basic'],
            ['name' => 'Electric Fan', 'icon' => 'fan', 'category' => 'basic'],
            ['name' => 'Water Heater', 'icon' => 'fire', 'category' => 'basic'],
            ['name' => 'Television', 'icon' => 'tv', 'category' => 'basic'],
            ['name' => 'Refrigerator', 'icon' => 'cube', 'category' => 'basic'],
            ['name' => 'Washing Machine', 'icon' => 'cog', 'category' => 'basic'],
            ['name' => 'Microwave', 'icon' => 'lightning-bolt', 'category' => 'basic'],

            // Kitchen
            ['name' => 'Kitchen', 'icon' => 'fire', 'category' => 'kitchen'],
            ['name' => 'Cooking Stove', 'icon' => 'fire', 'category' => 'kitchen'],
            ['name' => 'Kitchen Utensils', 'icon' => 'collection', 'category' => 'kitchen'],
            ['name' => 'Dining Area', 'icon' => 'table', 'category' => 'kitchen'],

            // Bathroom
            ['name' => 'Private Bathroom', 'icon' => 'sparkles', 'category' => 'bathroom'],
            ['name' => 'Shared Bathroom', 'icon' => 'user-group', 'category' => 'bathroom'],
            ['name' => 'Bidet', 'icon' => 'sparkles', 'category' => 'bathroom'],

            // Building/Security
            ['name' => 'Parking', 'icon' => 'truck', 'category' => 'building'],
            ['name' => '24/7 Security', 'icon' => 'shield-check', 'category' => 'building'],
            ['name' => 'CCTV', 'icon' => 'video-camera', 'category' => 'building'],
            ['name' => 'Elevator', 'icon' => 'arrow-up', 'category' => 'building'],
            ['name' => 'Fire Extinguisher', 'icon' => 'fire', 'category' => 'building'],
            ['name' => 'Smoke Detector', 'icon' => 'bell', 'category' => 'building'],
            ['name' => 'Key Card Access', 'icon' => 'key', 'category' => 'building'],

            // Outdoor/Recreation
            ['name' => 'Swimming Pool', 'icon' => 'sparkles', 'category' => 'outdoor'],
            ['name' => 'Gym/Fitness Center', 'icon' => 'heart', 'category' => 'outdoor'],
            ['name' => 'Garden', 'icon' => 'sparkles', 'category' => 'outdoor'],
            ['name' => 'Balcony/Terrace', 'icon' => 'sun', 'category' => 'outdoor'],
            ['name' => 'Rooftop Access', 'icon' => 'sun', 'category' => 'outdoor'],

            // Furnishing
            ['name' => 'Bed Frame', 'icon' => 'cube', 'category' => 'furnishing'],
            ['name' => 'Mattress', 'icon' => 'cube', 'category' => 'furnishing'],
            ['name' => 'Wardrobe/Closet', 'icon' => 'collection', 'category' => 'furnishing'],
            ['name' => 'Study Desk', 'icon' => 'desktop-computer', 'category' => 'furnishing'],
            ['name' => 'Sofa', 'icon' => 'cube', 'category' => 'furnishing'],

            // Others
            ['name' => 'Laundry Area', 'icon' => 'collection', 'category' => 'other'],
            ['name' => 'Water Tank/Cistern', 'icon' => 'cube', 'category' => 'other'],
            ['name' => 'Generator Backup', 'icon' => 'lightning-bolt', 'category' => 'other'],
            ['name' => 'Visitor Parking', 'icon' => 'truck', 'category' => 'other'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}
