<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_properties' => Property::count(),
            'total_bookings' => Booking::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
        ];

        $recentBookings = Booking::with(['user', 'property'])
            ->latest()
            ->take(5)
            ->get();

        $pendingTransactions = Transaction::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'pendingTransactions'));
    }
}
