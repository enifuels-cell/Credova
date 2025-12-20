<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Notifications\BookingConfirmed;
use App\Notifications\NewBookingRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('landlord')) {
            // For landlords, show bookings for their properties
            $properties = $user->properties()->pluck('id');
            $bookings = Booking::whereIn('property_id', $properties)
                ->with(['property', 'user'])
                ->latest()
                ->paginate(10);
        } else {
            // For renters, show their own bookings
            $bookings = $user->bookings()->with('property')->latest()->paginate(10);
        }
        
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create(Property $property)
    {
        return view('bookings.create', compact('property'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $property = Property::findOrFail($request->property_id);
        
        // Calculate total price
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $nights = $endDate->diffInDays($startDate);
        $totalPrice = $nights * $property->price_per_night;

        // Check for overlapping bookings
        $conflictingBooking = Booking::where('property_id', $property->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })->exists();

        if ($conflictingBooking) {
            return redirect()->back()->withErrors(['dates' => 'These dates are not available.']);
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'special_requests' => $request->special_requests
        ]);

        // Notify property owner about new booking request
        $property->user->notify(new NewBookingRequest($booking));

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking request submitted successfully!');
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Confirm a booking (for property owners/admins)
     */
    public function confirm(Booking $booking)
    {
        $this->authorize('update', $booking);
        
        $booking->update(['status' => 'confirmed']);
        
        // Send notification to the user who made the booking
        $booking->user->notify(new BookingConfirmed($booking));
        
        return redirect()->back()->with('success', 'Booking confirmed and notification sent!');
    }

    /**
     * Cancel a booking
     */
    public function cancel(Booking $booking)
    {
        $this->authorize('update', $booking);
        
        $booking->update(['status' => 'cancelled']);
        
        return redirect()->back()->with('success', 'Booking cancelled successfully!');
    }

    /**
     * Show the form for editing the specified booking
     */
    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);
        
        return view('bookings.edit', compact('booking'));
    }

    /**
     * Update the specified booking
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);
        
        $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        // Calculate total price
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $nights = $endDate->diffInDays($startDate);
        $totalPrice = $nights * $booking->property->price_per_night;

        // Check for overlapping bookings (excluding current booking)
        $conflictingBooking = Booking::where('property_id', $booking->property_id)
            ->where('id', '!=', $booking->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })->exists();

        if ($conflictingBooking) {
            return redirect()->back()->withErrors(['dates' => 'These dates are not available.']);
        }

        $booking->update([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $totalPrice,
            'special_requests' => $request->special_requests,
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated successfully!');
    }

    /**
     * Remove the specified booking from storage
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);
        
        $booking->delete();
        
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully!');
    }

    /**
     * Display bookings for a specific user (renters)
     */
    public function userBookings()
    {
        $bookings = Auth::user()->bookings()
            ->with('property')
            ->latest()
            ->paginate(10);
            
        return view('bookings.user-index', compact('bookings'));
    }
}
