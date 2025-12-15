<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display all bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['property', 'tenant', 'landlord']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                    ->orWhereHas('property', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tenant', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->latest()->paginate(15);

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'active' => Booking::where('status', 'active')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        $booking->load(['property.images', 'property.barangay', 'property.propertyType', 'tenant', 'landlord']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Delete booking
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}
