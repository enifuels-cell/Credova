<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\collector;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '09171234567',
                'address' => 'Admin Office, Manila',
            ]
        );

        // Create collector Users
        $collector1Data = [
            'email' => 'collector@example.com',
            'name' => 'Juan Santos',
            'password' => Hash::make('password'),
            'role' => 'collector',
            'phone' => '09181234567',
            'address' => 'Makati City',
        ];

        $collector1User = User::firstOrCreate(
            ['email' => 'collector@example.com'],
            $collector1Data
        );

        // Create associated collector record
        collector::firstOrCreate(
            ['user_id' => $collector1User->id],
            [
                'name' => 'Juan Santos',
                'email' => 'collector@example.com',
                'phone' => '09181234567',
            ]
        );

        // Create second collector
        $collector2Data = [
            'email' => 'collector2@example.com',
            'name' => 'Maria Cruz',
            'password' => Hash::make('password'),
            'role' => 'collector',
            'phone' => '09191234567',
            'address' => 'Quezon City',
        ];

        $collector2User = User::firstOrCreate(
            ['email' => 'collector2@example.com'],
            $collector2Data
        );

        collector::firstOrCreate(
            ['user_id' => $collector2User->id],
            [
                'name' => 'Maria Cruz',
                'email' => 'collector2@example.com',
                'phone' => '09191234567',
            ]
        );
    }
}
