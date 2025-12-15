<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Inquiry;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // User statistics
        $totalUsers = User::count();
        $totalLandlords = User::where('role', 'landlord')->count();
        $totalTenants = User::where('role', 'tenant')->count();

        // Property statistics
        $totalProperties = Property::count();
        $activeProperties = Property::active()->count();
        $pendingProperties = Property::where('status', 'pending')->count();
        $rentedProperties = Property::where('status', 'rented')->count();

        // Booking statistics
        $totalBookings = Booking::count();
        $pendingBookings = Booking::pending()->count();
        $activeBookings = Booking::active()->count();

        // Inquiry statistics
        $totalInquiries = Inquiry::count();
        $pendingInquiries = Inquiry::pending()->count();

        // Recent activities
        $recentUsers = User::latest()->take(5)->get();
        $recentProperties = Property::with(['landlord', 'propertyType'])->latest()->take(5)->get();
        $recentBookings = Booking::with(['property', 'tenant'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalLandlords',
            'totalTenants',
            'totalProperties',
            'activeProperties',
            'pendingProperties',
            'rentedProperties',
            'totalBookings',
            'pendingBookings',
            'activeBookings',
            'totalInquiries',
            'pendingInquiries',
            'recentUsers',
            'recentProperties',
            'recentBookings'
        ));
    }
}
