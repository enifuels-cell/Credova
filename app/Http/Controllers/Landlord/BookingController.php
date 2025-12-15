<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display landlord's bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['property.images', 'tenant'])
            ->where('landlord_id', Auth::id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10);

        // Stats
        $stats = [
            'pending' => Booking::where('landlord_id', Auth::id())->where('status', 'pending')->count(),
            'confirmed' => Booking::where('landlord_id', Auth::id())->where('status', 'confirmed')->count(),
            'active' => Booking::where('landlord_id', Auth::id())->where('status', 'active')->count(),
            'completed' => Booking::where('landlord_id', Auth::id())->where('status', 'completed')->count(),
        ];

        return view('landlord.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        // Check authorization
        if ($booking->landlord_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['property.images', 'property.barangay', 'property.propertyType', 'tenant']);

        return view('landlord.bookings.show', compact('booking'));
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        // Check authorization
        if ($booking->landlord_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,active,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        // If confirmed and property is active, optionally update property status
        if ($request->status === 'active') {
            $booking->property->update(['status' => 'rented']);
        } elseif ($request->status === 'completed' || $request->status === 'cancelled') {
            // Check if property has other active bookings
            $hasActiveBookings = Booking::where('property_id', $booking->property_id)
                ->where('id', '!=', $booking->id)
                ->where('status', 'active')
                ->exists();
            
            if (!$hasActiveBookings) {
                $booking->property->update(['status' => 'active']);
            }
        }

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }
}
