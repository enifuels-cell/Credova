<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@homygo.ph',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+63 912 345 6789',
            'email_verified_at' => now(),
        ]);

        // Create Sample Landlord
        User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'landlord@homygo.ph',
            'password' => Hash::make('password'),
            'role' => 'landlord',
            'phone' => '+63 917 123 4567',
            'email_verified_at' => now(),
        ]);

        // Create Sample Tenant
        User::create([
            'name' => 'Maria Santos',
            'email' => 'tenant@homygo.ph',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'phone' => '+63 918 765 4321',
            'email_verified_at' => now(),
        ]);
    }
}
