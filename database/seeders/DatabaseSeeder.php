<?php

namespace Database\Seeders;

<<<<<<< HEAD
=======
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        $this->call([
            BarangaySeeder::class,
            PropertyTypeSeeder::class,
            AmenitySeeder::class,
            AdminUserSeeder::class,
            PropertySeeder::class,
=======
        // Run role seeder first
        $this->call([
            RoleSeeder::class,
            TestUsersSeeder::class,
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
        ]);
    }
}
