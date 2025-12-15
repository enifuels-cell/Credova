<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the tenant dashboard.
     */
    public function index()
    {
        // Redirect admins and landlords to their respective dashboards
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->isLandlord()) {
            return redirect()->route('landlord.dashboard');
        }

        // For tenants, show dashboard with latest properties
        $latestProperties = Property::where('status', 'available')
            ->latest()
            ->take(8)
            ->with(['images', 'propertyType', 'barangay', 'user'])
            ->get();

        return view('dashboard', compact('latestProperties'));
    }
}
