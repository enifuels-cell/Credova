<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display landlord dashboard
     */
    public function index()
    {
        $userId = Auth::id();

        // Statistics
        $totalProperties = Property::where('user_id', $userId)->count();
        $activeProperties = Property::where('user_id', $userId)->active()->count();
        $rentedProperties = Property::where('user_id', $userId)->where('status', 'rented')->count();
        $pendingInquiries = Inquiry::whereHas('property', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->pending()->count();

        $pendingBookings = Booking::where('landlord_id', $userId)->pending()->count();
        $activeBookings = Booking::where('landlord_id', $userId)->active()->count();

        // Total views across all properties
        $totalViews = Property::where('user_id', $userId)->sum('views_count');

        // Recent inquiries
        $recentInquiries = Inquiry::with(['property.images'])
            ->whereHas('property', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->take(5)
            ->get();

        // Recent bookings
        $recentBookings = Booking::with(['property.images', 'tenant'])
            ->where('landlord_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('landlord.dashboard', compact(
            'totalProperties',
            'activeProperties',
            'rentedProperties',
            'pendingInquiries',
            'pendingBookings',
            'activeBookings',
            'totalViews',
            'recentInquiries',
            'recentBookings'
        ));
    }
}
