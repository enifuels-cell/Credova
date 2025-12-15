<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            // Long-Term Rentals
            [
                'name' => 'Apartment',
                'slug' => 'apartment',
                'category' => 'rental',
                'description' => 'A self-contained housing unit that occupies only part of a building',
                'icon' => 'building',
            ],
            [
                'name' => 'Condominium',
                'slug' => 'condominium',
                'category' => 'rental',
                'description' => 'A building or complex of buildings with individually owned apartments',
                'icon' => 'office-building',
            ],
            [
                'name' => 'House',
                'slug' => 'house',
                'category' => 'rental',
                'description' => 'A standalone residential building',
                'icon' => 'home',
            ],
            [
                'name' => 'Room',
                'slug' => 'room',
                'category' => 'rental',
                'description' => 'A single room for rent, typically in a shared house or dormitory',
                'icon' => 'door-closed',
            ],
            [
                'name' => 'Boarding House',
                'slug' => 'boarding-house',
                'category' => 'rental',
                'description' => 'A house where rooms are rented with meals often included',
                'icon' => 'users',
            ],
            [
                'name' => 'Townhouse',
                'slug' => 'townhouse',
                'category' => 'rental',
                'description' => 'A tall, narrow, traditional row house',
                'icon' => 'home-modern',
            ],
            [
                'name' => 'Studio',
                'slug' => 'studio',
                'category' => 'rental',
                'description' => 'A small apartment with combined living and sleeping areas',
                'icon' => 'cube',
            ],
            [
                'name' => 'Commercial Space',
                'slug' => 'commercial-space',
                'category' => 'rental',
                'description' => 'Space for business or commercial use',
                'icon' => 'storefront',
            ],

            // Stays & Short-Term Rentals
            [
                'name' => 'Hotel',
                'slug' => 'hotel',
                'category' => 'stays',
                'description' => 'A commercial establishment providing lodging, meals, and services',
                'icon' => 'building',
            ],
            [
                'name' => 'Boutique Hotel',
                'slug' => 'boutique-hotel',
                'category' => 'stays',
                'description' => 'A small stylish hotel with unique character and personalized service',
                'icon' => 'sparkles',
            ],
            [
                'name' => 'Motel',
                'slug' => 'motel',
                'category' => 'stays',
                'description' => 'A roadside hotel designed primarily for motorists',
                'icon' => 'truck',
            ],
            [
                'name' => 'Inn',
                'slug' => 'inn',
                'category' => 'stays',
                'description' => 'A small hotel providing accommodation, food, and drink',
                'icon' => 'home',
            ],
            [
                'name' => 'Bed & Breakfast',
                'slug' => 'bed-and-breakfast',
                'category' => 'stays',
                'description' => 'A small lodging offering overnight accommodation and breakfast',
                'icon' => 'sun',
            ],
            [
                'name' => 'Guesthouse',
                'slug' => 'guesthouse',
                'category' => 'stays',
                'description' => 'A private house offering accommodation to paying guests',
                'icon' => 'users',
            ],
            [
                'name' => 'Hostel',
                'slug' => 'hostel',
                'category' => 'stays',
                'description' => 'A budget-friendly accommodation with shared facilities',
                'icon' => 'user-group',
            ],
            [
                'name' => 'Resort',
                'slug' => 'resort',
                'category' => 'stays',
                'description' => 'A place with amenities for relaxation and recreation',
                'icon' => 'sparkles',
            ],
            [
                'name' => 'Lodge',
                'slug' => 'lodge',
                'category' => 'stays',
                'description' => 'A small house or cabin used for temporary stays',
                'icon' => 'home',
            ],
            [
                'name' => 'Serviced Apartment',
                'slug' => 'serviced-apartment',
                'category' => 'stays',
                'description' => 'A fully furnished apartment with hotel-like services',
                'icon' => 'office-building',
            ],
            [
                'name' => 'Vacation Rental',
                'slug' => 'vacation-rental',
                'category' => 'stays',
                'description' => 'A furnished property rented for short vacation stays',
                'icon' => 'sun',
            ],
            [
                'name' => 'Holiday Home',
                'slug' => 'holiday-home',
                'category' => 'stays',
                'description' => 'A property used for holidays and leisure getaways',
                'icon' => 'home',
            ],

            // Event Spaces
            [
                'name' => 'Function Hall',
                'slug' => 'function-hall',
                'category' => 'events',
                'description' => 'A large hall for events, parties, and gatherings',
                'icon' => 'calendar',
            ],
            [
                'name' => 'Conference Room',
                'slug' => 'conference-room',
                'category' => 'events',
                'description' => 'A room designed for meetings and conferences',
                'icon' => 'users',
            ],
            [
                'name' => 'Wedding Venue',
                'slug' => 'wedding-venue',
                'category' => 'events',
                'description' => 'A beautiful venue for weddings and receptions',
                'icon' => 'heart',
            ],
            [
                'name' => 'Party Venue',
                'slug' => 'party-venue',
                'category' => 'events',
                'description' => 'A space perfect for birthday parties and celebrations',
                'icon' => 'sparkles',
            ],
            [
                'name' => 'Coworking Space',
                'slug' => 'coworking-space',
                'category' => 'events',
                'description' => 'A shared workspace for professionals and freelancers',
                'icon' => 'desktop-computer',
            ],
            [
                'name' => 'Training Room',
                'slug' => 'training-room',
                'category' => 'events',
                'description' => 'A room equipped for training sessions and workshops',
                'icon' => 'academic-cap',
            ],
            [
                'name' => 'Outdoor Venue',
                'slug' => 'outdoor-venue',
                'category' => 'events',
                'description' => 'An open-air space for outdoor events and activities',
                'icon' => 'sun',
            ],
            [
                'name' => 'Studio Space',
                'slug' => 'studio-space',
                'category' => 'events',
                'description' => 'A creative space for photoshoots, recordings, and productions',
                'icon' => 'camera',
            ],
        ];

        foreach ($types as $type) {
            PropertyType::create($type);
        }
    }
}
