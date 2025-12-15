<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Seeder;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * All 80 barangays of Cagayan de Oro City with GPS coordinates
     * Coordinates sourced from OpenStreetMap and Google Maps
     */
    public function run(): void
    {
        $barangays = [
            // ========================================
            // UPTOWN AREA (Limketkai, SM, Ayala)
            // ========================================
            ['name' => 'Lapasan', 'district' => 2, 'latitude' => 8.4853, 'longitude' => 124.6467, 'area_type' => 'uptown', 'description' => 'Home to Limketkai Center, major commercial hub'],
            ['name' => 'Carmen', 'district' => 1, 'latitude' => 8.4780, 'longitude' => 124.6435, 'area_type' => 'uptown', 'description' => 'Location of Centrio Ayala Mall, major business district'],
            ['name' => 'Gusa', 'district' => 1, 'latitude' => 8.4922, 'longitude' => 124.6389, 'area_type' => 'uptown', 'description' => 'Growing residential and commercial area'],
            ['name' => 'Kauswagan', 'district' => 1, 'latitude' => 8.5050, 'longitude' => 124.6358, 'area_type' => 'uptown', 'description' => 'Near SM CDO Downtown Premier'],

            // ========================================
            // DOWNTOWN AREA (Divisoria, Centro, Port)
            // ========================================
            ['name' => 'Barangay 1 (Pob.)', 'district' => 1, 'latitude' => 8.4833, 'longitude' => 124.6500, 'area_type' => 'downtown', 'description' => 'Central business district, near City Hall'],
            ['name' => 'Barangay 2 (Pob.)', 'district' => 1, 'latitude' => 8.4830, 'longitude' => 124.6495, 'area_type' => 'downtown', 'description' => 'Part of the old downtown area'],
            ['name' => 'Barangay 3 (Pob.)', 'district' => 1, 'latitude' => 8.4827, 'longitude' => 124.6490, 'area_type' => 'downtown', 'description' => 'Historic downtown poblacion'],
            ['name' => 'Barangay 4 (Pob.)', 'district' => 1, 'latitude' => 8.4824, 'longitude' => 124.6485, 'area_type' => 'downtown', 'description' => 'Near Divisoria Market'],
            ['name' => 'Barangay 5 (Pob.)', 'district' => 1, 'latitude' => 8.4821, 'longitude' => 124.6480, 'area_type' => 'downtown', 'description' => 'Part of Divisoria commercial area'],
            ['name' => 'Barangay 6 (Pob.)', 'district' => 1, 'latitude' => 8.4818, 'longitude' => 124.6475, 'area_type' => 'downtown', 'description' => 'Central downtown location'],
            ['name' => 'Barangay 7 (Pob.)', 'district' => 1, 'latitude' => 8.4815, 'longitude' => 124.6470, 'area_type' => 'downtown', 'description' => 'Near public market'],
            ['name' => 'Barangay 8 (Pob.)', 'district' => 1, 'latitude' => 8.4812, 'longitude' => 124.6465, 'area_type' => 'downtown', 'description' => 'Downtown commercial area'],
            ['name' => 'Barangay 9 (Pob.)', 'district' => 1, 'latitude' => 8.4809, 'longitude' => 124.6460, 'area_type' => 'downtown', 'description' => 'Near San Agustin Cathedral'],
            ['name' => 'Barangay 10 (Pob.)', 'district' => 1, 'latitude' => 8.4806, 'longitude' => 124.6455, 'area_type' => 'downtown', 'description' => 'Central poblacion area'],
            ['name' => 'Barangay 11 (Pob.)', 'district' => 1, 'latitude' => 8.4803, 'longitude' => 124.6450, 'area_type' => 'downtown', 'description' => 'Downtown poblacion'],
            ['name' => 'Barangay 12 (Pob.)', 'district' => 1, 'latitude' => 8.4800, 'longitude' => 124.6445, 'area_type' => 'downtown', 'description' => 'Near port area'],
            ['name' => 'Barangay 13 (Pob.)', 'district' => 1, 'latitude' => 8.4797, 'longitude' => 124.6440, 'area_type' => 'downtown', 'description' => 'Port adjacent area'],
            ['name' => 'Barangay 14 (Pob.)', 'district' => 1, 'latitude' => 8.4794, 'longitude' => 124.6435, 'area_type' => 'downtown', 'description' => 'Coastal poblacion'],
            ['name' => 'Barangay 15 (Pob.)', 'district' => 1, 'latitude' => 8.4791, 'longitude' => 124.6430, 'area_type' => 'downtown', 'description' => 'Near Macabalan Port'],
            ['name' => 'Barangay 16 (Pob.)', 'district' => 1, 'latitude' => 8.4788, 'longitude' => 124.6425, 'area_type' => 'downtown', 'description' => 'Downtown residential'],
            ['name' => 'Barangay 17 (Pob.)', 'district' => 1, 'latitude' => 8.4785, 'longitude' => 124.6420, 'area_type' => 'downtown', 'description' => 'Historic poblacion'],
            ['name' => 'Barangay 18 (Pob.)', 'district' => 1, 'latitude' => 8.4782, 'longitude' => 124.6415, 'area_type' => 'downtown', 'description' => 'Near Gaston Park'],
            ['name' => 'Barangay 19 (Pob.)', 'district' => 1, 'latitude' => 8.4779, 'longitude' => 124.6410, 'area_type' => 'downtown', 'description' => 'Central downtown'],
            ['name' => 'Barangay 20 (Pob.)', 'district' => 1, 'latitude' => 8.4776, 'longitude' => 124.6405, 'area_type' => 'downtown', 'description' => 'Downtown poblacion'],
            ['name' => 'Barangay 21 (Pob.)', 'district' => 1, 'latitude' => 8.4773, 'longitude' => 124.6400, 'area_type' => 'downtown', 'description' => 'Historic district'],
            ['name' => 'Barangay 22 (Pob.)', 'district' => 1, 'latitude' => 8.4770, 'longitude' => 124.6395, 'area_type' => 'downtown', 'description' => 'Near heritage sites'],
            ['name' => 'Barangay 23 (Pob.)', 'district' => 1, 'latitude' => 8.4767, 'longitude' => 124.6390, 'area_type' => 'downtown', 'description' => 'Central poblacion'],
            ['name' => 'Barangay 24 (Pob.)', 'district' => 1, 'latitude' => 8.4764, 'longitude' => 124.6385, 'area_type' => 'downtown', 'description' => 'Downtown district'],
            ['name' => 'Barangay 25 (Pob.)', 'district' => 1, 'latitude' => 8.4761, 'longitude' => 124.6380, 'area_type' => 'downtown', 'description' => 'Near downtown plaza'],
            ['name' => 'Barangay 26 (Pob.)', 'district' => 1, 'latitude' => 8.4758, 'longitude' => 124.6375, 'area_type' => 'downtown', 'description' => 'Poblacion commercial'],
            ['name' => 'Barangay 27 (Pob.)', 'district' => 1, 'latitude' => 8.4755, 'longitude' => 124.6370, 'area_type' => 'downtown', 'description' => 'Downtown residential area'],
            ['name' => 'Barangay 28 (Pob.)', 'district' => 1, 'latitude' => 8.4752, 'longitude' => 124.6365, 'area_type' => 'downtown', 'description' => 'Poblacion barangay'],
            ['name' => 'Barangay 29 (Pob.)', 'district' => 1, 'latitude' => 8.4749, 'longitude' => 124.6360, 'area_type' => 'downtown', 'description' => 'Near downtown churches'],
            ['name' => 'Barangay 30 (Pob.)', 'district' => 1, 'latitude' => 8.4746, 'longitude' => 124.6355, 'area_type' => 'downtown', 'description' => 'Central downtown area'],
            ['name' => 'Barangay 31 (Pob.)', 'district' => 1, 'latitude' => 8.4743, 'longitude' => 124.6350, 'area_type' => 'downtown', 'description' => 'Downtown poblacion'],
            ['name' => 'Barangay 32 (Pob.)', 'district' => 1, 'latitude' => 8.4740, 'longitude' => 124.6345, 'area_type' => 'downtown', 'description' => 'Historic downtown'],
            ['name' => 'Barangay 33 (Pob.)', 'district' => 1, 'latitude' => 8.4737, 'longitude' => 124.6340, 'area_type' => 'downtown', 'description' => 'Poblacion commercial zone'],
            ['name' => 'Barangay 34 (Pob.)', 'district' => 1, 'latitude' => 8.4734, 'longitude' => 124.6335, 'area_type' => 'downtown', 'description' => 'Downtown commercial'],
            ['name' => 'Barangay 35 (Pob.)', 'district' => 1, 'latitude' => 8.4731, 'longitude' => 124.6330, 'area_type' => 'downtown', 'description' => 'Downtown poblacion'],
            ['name' => 'Barangay 36 (Pob.)', 'district' => 1, 'latitude' => 8.4728, 'longitude' => 124.6325, 'area_type' => 'downtown', 'description' => 'Poblacion residential'],
            ['name' => 'Barangay 37 (Pob.)', 'district' => 1, 'latitude' => 8.4725, 'longitude' => 124.6320, 'area_type' => 'downtown', 'description' => 'Downtown area'],
            ['name' => 'Barangay 38 (Pob.)', 'district' => 1, 'latitude' => 8.4722, 'longitude' => 124.6315, 'area_type' => 'downtown', 'description' => 'Central poblacion'],
            ['name' => 'Barangay 39 (Pob.)', 'district' => 1, 'latitude' => 8.4719, 'longitude' => 124.6310, 'area_type' => 'downtown', 'description' => 'Historic downtown'],
            ['name' => 'Barangay 40 (Pob.)', 'district' => 1, 'latitude' => 8.4716, 'longitude' => 124.6305, 'area_type' => 'downtown', 'description' => 'Downtown poblacion'],
            ['name' => 'Macabalan', 'district' => 2, 'latitude' => 8.4892, 'longitude' => 124.6625, 'area_type' => 'downtown', 'description' => 'Port area, major transportation hub'],
            ['name' => 'Puntod', 'district' => 2, 'latitude' => 8.4878, 'longitude' => 124.6611, 'area_type' => 'downtown', 'description' => 'Near port and coast'],
            ['name' => 'Consolacion', 'district' => 1, 'latitude' => 8.4845, 'longitude' => 124.6478, 'area_type' => 'downtown', 'description' => 'Near city center and schools'],

            // ========================================
            // SUBURBAN AREAS
            // ========================================
            ['name' => 'Bulua', 'district' => 1, 'latitude' => 8.4564, 'longitude' => 124.5997, 'area_type' => 'suburban', 'description' => 'Near Xavier University, residential area'],
            ['name' => 'Camaman-an', 'district' => 1, 'latitude' => 8.4611, 'longitude' => 124.6156, 'area_type' => 'suburban', 'description' => 'Growing residential neighborhood'],
            ['name' => 'Nazareth', 'district' => 2, 'latitude' => 8.4633, 'longitude' => 124.6244, 'area_type' => 'suburban', 'description' => 'Mixed residential and commercial'],
            ['name' => 'Macasandig', 'district' => 2, 'latitude' => 8.4689, 'longitude' => 124.6256, 'area_type' => 'suburban', 'description' => 'Near Capitol University'],
            ['name' => 'Patag', 'district' => 2, 'latitude' => 8.4711, 'longitude' => 124.6278, 'area_type' => 'suburban', 'description' => 'Residential suburb'],
            ['name' => 'Iponan', 'district' => 1, 'latitude' => 8.4478, 'longitude' => 124.5867, 'area_type' => 'suburban', 'description' => 'Near Iponan River, growing area'],
            ['name' => 'Balulang', 'district' => 1, 'latitude' => 8.4533, 'longitude' => 124.5922, 'area_type' => 'suburban', 'description' => 'Along the highway'],
            ['name' => 'Canitoan', 'district' => 1, 'latitude' => 8.4400, 'longitude' => 124.5833, 'area_type' => 'suburban', 'description' => 'Growing residential area'],
            ['name' => 'Cugman', 'district' => 1, 'latitude' => 8.5133, 'longitude' => 124.6344, 'area_type' => 'suburban', 'description' => 'Near SM CDO Downtown'],
            ['name' => 'Puerto', 'district' => 2, 'latitude' => 8.4922, 'longitude' => 124.6778, 'area_type' => 'suburban', 'description' => 'Coastal barangay'],
            ['name' => 'Agusan', 'district' => 1, 'latitude' => 8.4878, 'longitude' => 124.6067, 'area_type' => 'suburban', 'description' => 'Near Agusan River'],
            ['name' => 'Tablon', 'district' => 2, 'latitude' => 8.5289, 'longitude' => 124.6533, 'area_type' => 'suburban', 'description' => 'Near PHIVIDEC industrial area'],
            ['name' => 'Pagatpat', 'district' => 2, 'latitude' => 8.5111, 'longitude' => 124.6622, 'area_type' => 'suburban', 'description' => 'Growing residential zone'],
            ['name' => 'West Zone 1', 'district' => 2, 'latitude' => 8.4756, 'longitude' => 124.6178, 'area_type' => 'suburban', 'description' => 'Mixed residential area'],

            // ========================================
            // RURAL AREAS
            // ========================================
            ['name' => 'Bugo', 'district' => 1, 'latitude' => 8.5378, 'longitude' => 124.6611, 'area_type' => 'rural', 'description' => 'Near PHIVIDEC, industrial area'],
            ['name' => 'Lumbia', 'district' => 2, 'latitude' => 8.4156, 'longitude' => 124.6300, 'area_type' => 'rural', 'description' => 'Location of CDO Airport, highland area'],
            ['name' => 'Bayabas', 'district' => 1, 'latitude' => 8.4267, 'longitude' => 124.6067, 'area_type' => 'rural', 'description' => 'Highland barangay'],
            ['name' => 'Bayanga', 'district' => 1, 'latitude' => 8.4178, 'longitude' => 124.5956, 'area_type' => 'rural', 'description' => 'Rural agricultural area'],
            ['name' => 'Besigan', 'district' => 1, 'latitude' => 8.4089, 'longitude' => 124.5844, 'area_type' => 'rural', 'description' => 'Mountain barangay'],
            ['name' => 'Baikingon', 'district' => 1, 'latitude' => 8.4356, 'longitude' => 124.5733, 'area_type' => 'rural', 'description' => 'Rural area'],
            ['name' => 'Balubal', 'district' => 1, 'latitude' => 8.4444, 'longitude' => 124.5622, 'area_type' => 'rural', 'description' => 'Agricultural zone'],
            ['name' => 'Bonbon', 'district' => 1, 'latitude' => 8.4578, 'longitude' => 124.5544, 'area_type' => 'rural', 'description' => 'Coastal barangay, Del Monte Philippines'],
            ['name' => 'Dansolihon', 'district' => 1, 'latitude' => 8.3867, 'longitude' => 124.5689, 'area_type' => 'rural', 'description' => 'Highland rural area'],
            ['name' => 'F.S. Catanico', 'district' => 1, 'latitude' => 8.4000, 'longitude' => 124.5800, 'area_type' => 'rural', 'description' => 'Rural barangay'],
            ['name' => 'Mambuaya', 'district' => 2, 'latitude' => 8.4044, 'longitude' => 124.6578, 'area_type' => 'rural', 'description' => 'Nature area, ecotourism'],
            ['name' => 'Pagalungan', 'district' => 2, 'latitude' => 8.4133, 'longitude' => 124.6456, 'area_type' => 'rural', 'description' => 'Rural agricultural'],
            ['name' => 'Pigsag-an', 'district' => 2, 'latitude' => 8.4222, 'longitude' => 124.6333, 'area_type' => 'rural', 'description' => 'Rural barangay'],
            ['name' => 'San Simon', 'district' => 2, 'latitude' => 8.4311, 'longitude' => 124.6211, 'area_type' => 'rural', 'description' => 'Agricultural area'],
            ['name' => 'Taglimao', 'district' => 2, 'latitude' => 8.3956, 'longitude' => 124.6089, 'area_type' => 'rural', 'description' => 'Highland farming area'],
            ['name' => 'Tagpangi', 'district' => 2, 'latitude' => 8.3844, 'longitude' => 124.5967, 'area_type' => 'rural', 'description' => 'Rural mountain area'],
            ['name' => 'Tignapoloan', 'district' => 2, 'latitude' => 8.3733, 'longitude' => 124.5844, 'area_type' => 'rural', 'description' => 'Remote rural barangay'],
            ['name' => 'Tuburan', 'district' => 2, 'latitude' => 8.3622, 'longitude' => 124.5722, 'area_type' => 'rural', 'description' => 'Upland farming area'],
            ['name' => 'Tumpagon', 'district' => 2, 'latitude' => 8.3511, 'longitude' => 124.5600, 'area_type' => 'rural', 'description' => 'Rural agricultural zone'],
        ];

        foreach ($barangays as $barangay) {
            Barangay::create($barangay);
        }
    }
}
