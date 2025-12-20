<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@homygo.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->syncRoles(['admin']);

        // Create renter user
        $renter = User::firstOrCreate(
            ['email' => 'renter@homygo.com'],
            [
                'name' => 'John Renter',
                'password' => Hash::make('password123'),
            ]
        );
        $renter->syncRoles(['renter']);

        // Create landlord user
        $landlord = User::firstOrCreate(
            ['email' => 'landlord@homygo.com'],
            [
                'name' => 'Jane Landlord',
                'password' => Hash::make('password123'),
            ]
        );
        $landlord->syncRoles(['landlord']);

        echo "Test users created:\n";
        echo "Admin: admin@homygo.com / password123\n";
        echo "Renter: renter@homygo.com / password123\n";
        echo "Landlord: landlord@homygo.com / password123\n";
    }
}
