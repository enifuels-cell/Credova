<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Barangay;
use App\Models\User;
use App\Models\Amenity;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $landlord = User::where('role', 'landlord')->first();
        if (!$landlord) {
            $landlord = User::create([
                'name' => 'CDO Landlord',
                'email' => 'landlord@homygo.ph',
                'password' => bcrypt('password'),
                'role' => 'landlord',
            ]);
        }

        $amenities = Amenity::pluck('id')->toArray();
        $barangays = Barangay::pluck('id', 'name')->toArray();

        // Get property types
        $apartment = PropertyType::where('name', 'Apartment')->first();
        $house = PropertyType::where('name', 'House')->first();
        $condo = PropertyType::where('name', 'Condominium')->first();
        $studio = PropertyType::where('name', 'Studio Unit')->first();
        $boardingHouse = PropertyType::where('name', 'Boarding House')->first();
        $hotel = PropertyType::where('name', 'Hotel')->first();
        $resort = PropertyType::where('name', 'Resort')->first();
        $transient = PropertyType::where('name', 'Transient House')->first();
        $eventVenue = PropertyType::where('name', 'Event Venue')->first();
        $functionHall = PropertyType::where('name', 'Function Hall')->first();

        $properties = [
            // ============================================
            // HOTELS IN CAGAYAN DE ORO
            // ============================================
            [
                'title' => 'Seda Centrio',
                'description' => 'Seda Centrio is a premier hotel directly connected to Centrio Ayala Mall. Features modern rooms, Misto Restaurant, fitness center, and world-class service. Perfect for business and leisure travelers.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Centrio Ayala Mall, CM Recto Avenue, Carmen',
                'price' => 4500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 32,
                'is_featured' => true,
                'image_path' => 'seda.jpg', // Use public/seda.jpg
            ],
            [
                'title' => 'Limketkai Luxe Hotel',
                'description' => 'Limketkai Luxe Hotel offers premium accommodations in the heart of CDO. Rooftop infinity pool, spa, multiple restaurants, and direct access to Limketkai Center mall.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Lapasan'] ?? 2,
                'address' => 'Limketkai Center, Lapasan',
                'price' => 5500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 38,
                'is_featured' => true,
                'image_path' => 'download.jpg', // Use public/download.jpg
            ],
            [
                'title' => 'N Hotel Cagayan de Oro',
                'slug' => 'n-hotel-cagayan-de-oro',
                'description' => 'N Hotel offers contemporary accommodations with stylish rooms, complimentary breakfast, gym access, and strategic location near major roads and business centers.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Kauswagan'] ?? 3,
                'address' => 'Kauswagan Highway, Kauswagan',
                'price' => 2800,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 28,
                'is_featured' => true,
                'image_path' => 'N-Hotel.png', // Use public/N-Hotel.png
            ],
            [
                'title' => 'Mallberry Suites Business Hotel',
                'description' => 'Mallberry Suites offers comfortable business accommodations with free WiFi, meeting rooms, restaurant, and excellent service. Located along the highway for easy access.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Claro M. Recto Avenue, Carmen',
                'price' => 2200,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 25,
                'is_featured' => false,
                'image_path' => 'Malberry.png', // Use public/Malberry.png
            ],
            [
                'title' => 'Upperhouse Hotel',
                'description' => 'Upperhouse Hotel is a charming boutique hotel offering personalized service, elegant rooms, and a peaceful atmosphere. Features a garden restaurant and event spaces.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Jose V. Roa Street, Carmen',
                'price' => 2500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 26,
                'is_featured' => false,
            ],
            [
                'title' => 'Pearlmont Hotel',
                'description' => 'Pearlmont Hotel offers affordable luxury in downtown CDO. Clean rooms, free WiFi, complimentary breakfast, and friendly staff. Perfect for business or leisure travelers.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'Velez Street, Cogon',
                'price' => 1800,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 22,
                'is_featured' => false,
            ],
            [
                'title' => 'Citylight Hotel',
                'description' => 'Citylight Hotel is a budget-friendly hotel in downtown CDO. Air-conditioned rooms, 24-hour front desk, and walking distance to Divisoria and public transport.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'J.R. Borja Street, Cogon',
                'price' => 1200,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 18,
                'is_featured' => false,
            ],
            [
                'title' => 'Grand City Hotel',
                'description' => 'Grand City Hotel provides comfortable accommodations with modern amenities. Restaurant, conference rooms, and convenient location near business centers.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Hayes Street, Carmen',
                'price' => 2000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 24,
                'is_featured' => false,
            ],
            [
                'title' => 'RedDoorz @ Velez Cagayan de Oro',
                'description' => 'RedDoorz offers standardized budget accommodations with clean rooms, AC, WiFi, and consistent quality. Great value for money in downtown CDO.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'Velez Street, Cogon',
                'price' => 999,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 16,
                'is_featured' => false,
            ],
            [
                'title' => 'RedDoorz Plus @ Corrales Cagayan de Oro',
                'description' => 'RedDoorz Plus offers upgraded budget rooms with premium bedding, smart TV, and complimentary breakfast. Located along Corrales Avenue.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Corrales Avenue, Carmen',
                'price' => 1299,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 18,
                'is_featured' => false,
            ],
            [
                'title' => 'Hotel & Restaurant VJR',
                'description' => 'VJR Hotel offers comfortable rooms and an on-site restaurant serving Filipino and international cuisine. Affordable rates with warm hospitality.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Pabayo Street, Carmen',
                'price' => 1500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 20,
                'is_featured' => false,
            ],
            [
                'title' => 'Dynasty Court Hotel',
                'description' => 'Dynasty Court Hotel offers elegant accommodations with Chinese-inspired design. Features a restaurant, function rooms, and convenient downtown location.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'Tiano Brothers Street, Cogon',
                'price' => 1800,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 22,
                'is_featured' => false,
            ],
            [
                'title' => 'New Dawn Pensionne Hotel',
                'description' => 'New Dawn Pensionne is a budget-friendly option near the city center. Clean rooms, friendly staff, and affordable daily/weekly rates.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'Mabini Street, Cogon',
                'price' => 800,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 14,
                'is_featured' => false,
            ],

            // ============================================
            // INNS & LODGES IN CAGAYAN DE ORO
            // ============================================
            [
                'title' => 'Philtown Inn',
                'description' => 'Philtown Inn offers comfortable and affordable rooms in the heart of CDO. Air-conditioned rooms, cable TV, and walking distance to malls and restaurants.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Chavez Street, Carmen',
                'price' => 1000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 16,
                'is_featured' => false,
            ],
            [
                'title' => 'Sampaguita Tourist Inn',
                'description' => 'Sampaguita Tourist Inn is a budget-friendly accommodation with clean rooms, AC, and friendly service. Perfect for backpackers and budget travelers.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'A. Velez Street, Cogon',
                'price' => 700,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 14,
                'is_featured' => false,
            ],
            [
                'title' => 'Koresco Lodge',
                'description' => 'Koresco Lodge offers simple, clean accommodations at affordable rates. Convenient location with easy access to public transportation.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Corrales Avenue, Carmen',
                'price' => 600,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 12,
                'is_featured' => false,
            ],
            [
                'title' => 'Maxandrea Hotel',
                'description' => 'Maxandrea Hotel provides comfortable rooms with modern amenities at reasonable prices. Restaurant on-site and near major business establishments.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'C.M. Recto Avenue, Carmen',
                'price' => 1400,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 18,
                'is_featured' => false,
            ],
            [
                'title' => 'Rosevale Inn',
                'description' => 'Rosevale Inn offers homey accommodations with a garden setting. Clean rooms, home-cooked meals available, and peaceful environment.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Macasandig'] ?? 10,
                'address' => 'Masterson Avenue, Macasandig',
                'price' => 900,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 16,
                'is_featured' => false,
            ],
            [
                'title' => 'Pryce Plaza Hotel',
                'description' => 'Pryce Plaza Hotel is perched on a hilltop with panoramic city views. Features elegant rooms, swimming pool, tennis courts, and multiple dining options.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Carmen Hill, Carmen',
                'price' => 3500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 35,
                'is_featured' => true,
            ],

            // ============================================
            // SUITES IN CAGAYAN DE ORO
            // ============================================
            [
                'title' => 'DM Orvie Suites',
                'description' => 'DM Orvie Suites offers affordable transient rooms. Clean, comfortable, and budget-friendly perfect for travelers. Near Limketkai Mall with easy public transport access.',
                'property_type_id' => $transient?->id ?? 8,
                'barangay_id' => $barangays['Kauswagan'] ?? 3,
                'address' => 'Corrales Extension, Kauswagan',
                'price' => 1200,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 18,
                'is_featured' => false,
            ],
            [
                'title' => 'Apple Tree Suites',
                'description' => 'Apple Tree Suites offers modern serviced apartments with kitchenette, living area, and hotel amenities. Perfect for extended stays and business travelers.',
                'property_type_id' => $transient?->id ?? 8,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Capistrano Street, Carmen',
                'price' => 1800,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 28,
                'is_featured' => false,
            ],
            [
                'title' => 'Kokomo Suites',
                'description' => 'Kokomo Suites provides comfortable studio and one-bedroom units with modern amenities. Weekly and monthly rates available. Near business centers.',
                'property_type_id' => $transient?->id ?? 8,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Tomas Saco Street, Carmen',
                'price' => 1500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 22,
                'is_featured' => false,
            ],
            [
                'title' => 'Alpa City Suites',
                'description' => 'Alpa City Suites offers spacious rooms with city views, restaurant, pool, and function rooms. Great for both business and leisure guests.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'CM Recto Avenue, Carmen',
                'price' => 2200,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 26,
                'is_featured' => false,
            ],
            [
                'title' => 'Chali Beach Resort & Conference Center',
                'description' => 'Chali Beach Resort offers beachfront accommodations with cottages, conference facilities, and water sports. Perfect for company outings and retreats.',
                'property_type_id' => $resort?->id ?? 7,
                'barangay_id' => $barangays['Bugo'] ?? 8,
                'address' => 'Opol Highway, Bugo',
                'price' => 3000,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floor_area' => 40,
                'is_featured' => true,
            ],

            // ============================================
            // CONDOMINIUMS IN CAGAYAN DE ORO
            // ============================================
            [
                'title' => 'Mesaverte Residences',
                'description' => 'Premium condominium living at Mesaverte Residences! Fully furnished units with city views, swimming pool, fitness center, and 24/7 security. Walking distance to SM CDO Downtown.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Lapasan'] ?? 2,
                'address' => 'CM Recto Avenue, Lapasan',
                'price' => 25000,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floor_area' => 45,
                'is_featured' => true,
                'image_path' => 'mesaverte.jpg', // Use public/mesaverte.jpg
            ],
            [
                'title' => 'Primavera Residences',
                'description' => 'Primavera Residences by Italpinas offers Italian-inspired condo living. Resort-style amenities, piazza, pool, gym, and commercial spaces. Near Centrio Mall.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Mastersons Avenue, Carmen',
                'price' => 22000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 36,
                'is_featured' => true,
            ],
            [
                'title' => 'The Loop Tower by Italpinas',
                'description' => 'The Loop Tower is a modern high-rise condo with eco-friendly features. Rooftop garden, infinity pool, co-working spaces, and smart home features.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Limketkai Drive, Carmen',
                'price' => 28000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 32,
                'is_featured' => true,
            ],
            [
                'title' => 'One Oasis Cagayan de Oro',
                'description' => 'One Oasis CDO by Filinvest offers resort-inspired living. Sprawling gardens, lagoon pool, clubhouse, and 24/7 security. Family-friendly community.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Lumbia'] ?? 7,
                'address' => 'Pueblo de Oro, Lumbia',
                'price' => 18000,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floor_area' => 40,
                'is_featured' => true,
            ],
            [
                'title' => 'Camella Manors Bacolod CDO',
                'description' => 'Camella Manors offers affordable condo living with complete amenities. Pool, gym, function rooms, and retail spaces. Perfect for young professionals.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Corrales Avenue, Carmen',
                'price' => 15000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 24,
                'is_featured' => false,
            ],
            [
                'title' => 'Torre de Oro by Johndorf',
                'description' => 'Torre de Oro is a mid-rise condominium in Uptown CDO. Studio and 1-bedroom units with modern finishes, parking, and lifestyle amenities.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Mastersons Avenue, Uptown',
                'price' => 20000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 30,
                'is_featured' => false,
            ],
            [
                'title' => 'Avida Towers CDO',
                'description' => 'Avida Towers brings Ayala quality to CDO. Modern high-rise living with premium amenities, retail podium, and integrated lifestyle community.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Near Centrio Mall, Carmen',
                'price' => 23000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 28,
                'is_featured' => false,
            ],
            [
                'title' => '8 Spatial Davao Tower 1 CDO Satellite',
                'description' => 'Modern condo unit with smart home features. Fully furnished with WiFi, cable, and utilities included. Near universities and commercial centers.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Corrales Extension, Carmen',
                'price' => 16000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 22,
                'is_featured' => false,
            ],

            // ============================================
            // APARTMENTS FOR RENT
            // ============================================
            [
                'title' => 'Uptown CDO Apartment',
                'description' => 'Modern 2-bedroom apartment in Uptown CDO. Fully furnished with aircon, WiFi-ready, and parking. Near Xavier University and major establishments.',
                'property_type_id' => $apartment?->id ?? 1,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Corrales Avenue, Carmen',
                'price' => 15000,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floor_area' => 50,
                'is_featured' => true,
            ],
            [
                'title' => 'Pueblo de Oro Studio',
                'description' => 'Cozy studio unit in Pueblo de Oro subdivision. Gated community with 24/7 security, clubhouse access, and swimming pool. Ideal for young professionals.',
                'property_type_id' => $studio?->id ?? 4,
                'barangay_id' => $barangays['Cugman'] ?? 5,
                'address' => 'Pueblo de Oro Business Park',
                'price' => 10000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 28,
                'is_featured' => false,
            ],
            [
                'title' => 'Xavier Heights House',
                'description' => 'Spacious 3-bedroom house near Xavier University. Perfect for families or students. With garage, garden, and modern kitchen.',
                'property_type_id' => $house?->id ?? 2,
                'barangay_id' => $barangays['Balulang'] ?? 6,
                'address' => 'Xavier Heights Subdivision',
                'price' => 22000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'floor_area' => 120,
                'is_featured' => true,
            ],
            [
                'title' => 'Camella Homes CDO',
                'description' => 'Beautiful townhouse in Camella Homes subdivision. Family-friendly community with playground, basketball court, and 24/7 security.',
                'property_type_id' => $house?->id ?? 2,
                'barangay_id' => $barangays['Lumbia'] ?? 7,
                'address' => 'Camella Homes, Lumbia',
                'price' => 18000,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'floor_area' => 80,
                'is_featured' => false,
            ],
            [
                'title' => 'Capitol Boarding House',
                'description' => 'Affordable boarding house near Capitol University. Air-conditioned rooms, shared kitchen, WiFi included. Perfect for students.',
                'property_type_id' => $boardingHouse?->id ?? 5,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'Near Capitol University',
                'price' => 4500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 15,
                'is_featured' => false,
            ],
            [
                'title' => 'XU Student Dormitory',
                'description' => 'Student dormitory near Xavier University. Fully furnished rooms, study area, common kitchen, and secure environment. Utilities included.',
                'property_type_id' => $boardingHouse?->id ?? 5,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Near Xavier University, Carmen',
                'price' => 5000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 12,
                'is_featured' => false,
            ],
            [
                'title' => 'USTP Boarding House',
                'description' => 'Budget-friendly boarding house for USTP students. Walking distance to campus, with aircon, WiFi, and laundry area.',
                'property_type_id' => $boardingHouse?->id ?? 5,
                'barangay_id' => $barangays['Lapasan'] ?? 2,
                'address' => 'Near USTP Campus, Lapasan',
                'price' => 3500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 10,
                'is_featured' => false,
            ],

            // ============================================
            // RESORTS
            // ============================================
            [
                'title' => 'Dahilayan Adventure Park Resort',
                'description' => 'Experience adventure at Dahilayan! Perfect for team buildings and nature lovers. Zipline, horseback riding, and stunning mountain views await.',
                'property_type_id' => $resort?->id ?? 7,
                'barangay_id' => $barangays['Lumbia'] ?? 7,
                'address' => 'Camp Phillips, Manolo Fortich',
                'price' => 2500,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floor_area' => 45,
                'is_featured' => true,
            ],
            [
                'title' => 'Seven Seas Waterpark',
                'description' => 'The largest waterpark in Mindanao! Enjoy wave pools, lazy rivers, and thrilling slides. Cottages and function halls available for parties.',
                'property_type_id' => $resort?->id ?? 7,
                'barangay_id' => $barangays['Bugo'] ?? 8,
                'address' => 'Barra, Opol',
                'price' => 3500,
                'bedrooms' => 0,
                'bathrooms' => 2,
                'floor_area' => 50,
                'is_featured' => true,
            ],
            [
                'title' => 'Mapawa Nature Park',
                'description' => 'Eco-adventure destination with waterfalls, trekking trails, and riverside camping. Perfect for nature lovers seeking adventure near CDO.',
                'property_type_id' => $resort?->id ?? 7,
                'barangay_id' => $barangays['Cugman'] ?? 5,
                'address' => 'Mapawa, Cugman',
                'price' => 1500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 30,
                'is_featured' => false,
            ],
            [
                'title' => 'Gardens of Malasag Eco-Tourism Village',
                'description' => 'Cultural and ecological village showcasing Mindanao heritage. Native cottages, butterfly sanctuary, and panoramic city views.',
                'property_type_id' => $resort?->id ?? 7,
                'barangay_id' => $barangays['Cugman'] ?? 5,
                'address' => 'Malasag, Cugman',
                'price' => 2000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 35,
                'is_featured' => false,
            ],
            [
                'title' => 'Opol Beach Resort',
                'description' => 'Beachfront resort perfect for weekend getaways. Cottages, swimming pool, beach access, and fresh seafood restaurant.',
                'property_type_id' => $resort?->id ?? 7,
                'barangay_id' => $barangays['Bugo'] ?? 8,
                'address' => 'Opol Coastal Road',
                'price' => 2500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 35,
                'is_featured' => false,
            ],

            // ============================================
            // EVENT VENUES & FUNCTION HALLS
            // ============================================
            [
                'title' => 'Grand Caprice Events Place',
                'description' => 'Elegant events venue for weddings, debuts, and corporate events. Accommodates up to 500 guests with full catering, sound system, and parking.',
                'property_type_id' => $eventVenue?->id ?? 9,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Corrales Avenue, Carmen',
                'price' => 85000,
                'bedrooms' => 0,
                'bathrooms' => 4,
                'floor_area' => 500,
                'is_featured' => true,
            ],
            [
                'title' => 'Limketkai Atrium Function Hall',
                'description' => 'Premium function hall at Limketkai Center. Perfect for conferences, seminars, and social events. Modern facilities and professional support.',
                'property_type_id' => $functionHall?->id ?? 10,
                'barangay_id' => $barangays['Lapasan'] ?? 2,
                'address' => 'Limketkai Center',
                'price' => 45000,
                'bedrooms' => 0,
                'bathrooms' => 2,
                'floor_area' => 300,
                'is_featured' => false,
            ],
            [
                'title' => 'The Venue at SM CDO',
                'description' => 'Multi-purpose event space at SM City CDO. Ideal for exhibits, launches, and corporate gatherings. Full technical support available.',
                'property_type_id' => $functionHall?->id ?? 10,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'SM City CDO, Puerto',
                'price' => 55000,
                'bedrooms' => 0,
                'bathrooms' => 4,
                'floor_area' => 400,
                'is_featured' => false,
            ],
            [
                'title' => 'Garden Oasis Events',
                'description' => 'Beautiful garden venue for intimate weddings and parties. Natural scenery, fairy lights, and romantic ambiance. Capacity up to 200 guests.',
                'property_type_id' => $eventVenue?->id ?? 9,
                'barangay_id' => $barangays['Bugo'] ?? 8,
                'address' => 'Highway, Bugo',
                'price' => 35000,
                'bedrooms' => 0,
                'bathrooms' => 2,
                'floor_area' => 400,
                'is_featured' => false,
            ],
            [
                'title' => 'Rosalynn Events Place',
                'description' => 'Versatile events venue with indoor and outdoor spaces. Perfect for weddings, birthdays, and corporate events. Full catering services.',
                'property_type_id' => $eventVenue?->id ?? 9,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Upper Carmen',
                'price' => 40000,
                'bedrooms' => 0,
                'bathrooms' => 3,
                'floor_area' => 350,
                'is_featured' => false,
            ],
            [
                'title' => 'Centrio Ayala Convention Center',
                'slug' => 'centrio-ayala-convention-center',
                'description' => 'State-of-the-art convention facility for large-scale events. Modular spaces, advanced AV equipment, and premium catering options.',
                'property_type_id' => $functionHall?->id ?? 10,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Centrio Ayala Mall, Carmen',
                'price' => 120000,
                'bedrooms' => 0,
                'bathrooms' => 6,
                'floor_area' => 800,
                'is_featured' => true,
                'image_path' => 'centrio.png', // Use public/centrio.png
            ],

            // ============================================
            // UPTOWN CDO PROPERTIES
            // ============================================
            [
                'title' => 'Uptown Modern Loft',
                'description' => 'Stylish modern loft in Uptown CDO. Open floor plan, high ceilings, designer finishes. Walking distance to Ayala Centrio and Lifestyle District.',
                'property_type_id' => $apartment?->id ?? 1,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Uptown, Mastersons Avenue',
                'price' => 18000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 45,
                'is_featured' => true,
            ],
            [
                'title' => 'Uptown Executive Suite',
                'description' => 'Premium executive suite in Uptown area. Fully furnished with luxury amenities, concierge service, and panoramic city views.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Uptown Lifestyle District, Carmen',
                'price' => 35000,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'floor_area' => 65,
                'is_featured' => true,
            ],
            [
                'title' => 'Uptown Studio Pad',
                'description' => 'Cozy studio unit perfect for young professionals. Located in the vibrant Uptown area near cafes, restaurants, and malls.',
                'property_type_id' => $studio?->id ?? 4,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Uptown, Corrales Avenue',
                'price' => 12000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 25,
                'is_featured' => false,
            ],
            [
                'title' => 'Uptown Family Home',
                'description' => 'Spacious 4-bedroom house in quiet Uptown neighborhood. Large garden, 2-car garage, modern kitchen. Near international schools.',
                'property_type_id' => $house?->id ?? 2,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Uptown Subdivision, Carmen',
                'price' => 45000,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'floor_area' => 180,
                'is_featured' => true,
            ],
            [
                'title' => 'Uptown Business Hotel',
                'description' => 'Boutique business hotel in the Uptown district. Modern rooms, meeting facilities, rooftop lounge, and executive services.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Carmen'] ?? 1,
                'address' => 'Uptown, near Centrio Mall',
                'price' => 2800,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 28,
                'is_featured' => false,
            ],

            // ============================================
            // DOWNTOWN CDO PROPERTIES
            // ============================================
            [
                'title' => 'Downtown Heritage Apartment',
                'description' => 'Charming apartment in historic Downtown CDO. Classic architecture with modern updates. Walking distance to Divisoria and City Hall.',
                'property_type_id' => $apartment?->id ?? 1,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'Downtown, Velez Street',
                'price' => 10000,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floor_area' => 55,
                'is_featured' => false,
            ],
            [
                'title' => 'Downtown Budget Inn',
                'description' => 'Affordable accommodation in the heart of Downtown CDO. Clean rooms, AC, WiFi. Perfect for budget travelers and backpackers.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'Downtown, J.R. Borja Street',
                'price' => 800,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 16,
                'is_featured' => false,
            ],
            [
                'title' => 'Downtown Commercial Condo',
                'description' => 'Strategic condo unit in Downtown CDO. Ideal for professionals working in the city center. Near banks, government offices, and transport hubs.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'Downtown, Tiano Brothers Street',
                'price' => 14000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 32,
                'is_featured' => false,
            ],
            [
                'title' => 'Downtown Student Dormitory',
                'description' => 'Affordable dorm for students and young professionals. Downtown location with easy access to schools and public transport.',
                'property_type_id' => $boardingHouse?->id ?? 5,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'Downtown, near Divisoria',
                'price' => 3500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 12,
                'is_featured' => false,
            ],
            [
                'title' => 'Downtown Plaza Hotel',
                'description' => 'Classic hotel in Downtown CDO with traditional Filipino hospitality. Restaurant, function rooms, and central location.',
                'property_type_id' => $hotel?->id ?? 6,
                'barangay_id' => $barangays['Cogon'] ?? 4,
                'address' => 'Downtown, near City Hall',
                'price' => 1500,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 22,
                'is_featured' => false,
            ],
            [
                'title' => 'SM Downtown Condo Unit',
                'description' => 'Modern condo near SM CDO Downtown Premier. Fully furnished with mall access, pool, gym, and 24/7 security.',
                'property_type_id' => $condo?->id ?? 3,
                'barangay_id' => $barangays['Lapasan'] ?? 2,
                'address' => 'Downtown Premier, near SM CDO',
                'price' => 20000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor_area' => 35,
                'is_featured' => true,
            ],
        ];

        // Barangay coordinates for property geo-location
        $barangayCoords = [
            'Lapasan' => ['lat' => 8.4853, 'lng' => 124.6467],
            'Carmen' => ['lat' => 8.4780, 'lng' => 124.6435],
            'Gusa' => ['lat' => 8.4922, 'lng' => 124.6389],
            'Kauswagan' => ['lat' => 8.5050, 'lng' => 124.6358],
            'Cogon' => ['lat' => 8.4818, 'lng' => 124.6475],
            'Macasandig' => ['lat' => 8.4689, 'lng' => 124.6256],
            'Bulua' => ['lat' => 8.4564, 'lng' => 124.5997],
            'Bugo' => ['lat' => 8.5378, 'lng' => 124.6611],
            'Lumbia' => ['lat' => 8.4156, 'lng' => 124.6300],
            'Cugman' => ['lat' => 8.5133, 'lng' => 124.6344],
            'Macabalan' => ['lat' => 8.4892, 'lng' => 124.6625],
            'Puerto' => ['lat' => 8.4922, 'lng' => 124.6778],
            'Patag' => ['lat' => 8.4711, 'lng' => 124.6278],
            'default' => ['lat' => 8.4780, 'lng' => 124.6400],
        ];

        foreach ($properties as $propertyData) {
            // Get barangay name for coordinates
            $barangayName = array_search($propertyData['barangay_id'], $barangays);
            $coords = $barangayCoords[$barangayName] ?? $barangayCoords['default'];

            // Add small random offset to coordinates (within ~200m)
            $latOffset = (rand(-100, 100) / 10000);
            $lngOffset = (rand(-100, 100) / 10000);

            $property = Property::create([
                'user_id' => $landlord->id,
                'title' => $propertyData['title'],
                'description' => $propertyData['description'],
                'property_type_id' => $propertyData['property_type_id'],
                'barangay_id' => $propertyData['barangay_id'],
                'address' => $propertyData['address'],
                'price' => $propertyData['price'],
                'bedrooms' => $propertyData['bedrooms'],
                'bathrooms' => $propertyData['bathrooms'],
                'floor_area' => $propertyData['floor_area'],
                'is_featured' => $propertyData['is_featured'],
                'status' => 'active',
                'views_count' => rand(50, 500),
                'latitude' => $coords['lat'] + $latOffset,
                'longitude' => $coords['lng'] + $lngOffset,
            ]);

            // Attach image for Seda Centrio if image_path is set
            if (isset($propertyData['image_path'])) {
                $property->images()->create([
                    'image_path' => $propertyData['image_path'],
                    'is_primary' => true,
                ]);
            }

            // Attach random amenities
            if (count($amenities) > 0) {
                $randomAmenities = array_rand(array_flip($amenities), min(5, count($amenities)));
                if (!is_array($randomAmenities)) {
                    $randomAmenities = [$randomAmenities];
                }
                $property->amenities()->attach($randomAmenities);
            }
        }
    }
}
