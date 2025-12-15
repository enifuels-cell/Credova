<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display user's bookings (for tenants)
     */
    public function index()
    {
        $bookings = Booking::with(['property.images', 'property.barangay', 'landlord'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show booking creation form
     */
    public function create(Property $property)
    {
        // Only show active properties
        if ($property->status !== 'active') {
            abort(404);
        }

        return view('bookings.create', compact('property'));
    }

    /**
     * Store a new booking
     */
    public function store(BookingRequest $request, Property $property)
    {
        // Only allow booking active properties
        if ($property->status !== 'active') {
            return redirect()->back()->with('error', 'This property is not available for booking.');
        }

        $data = $request->validated();
        
        // Calculate end date
        $startDate = new \DateTime($data['start_date']);
        $endDate = clone $startDate;
        $endDate->modify('+' . $data['lease_duration_months'] . ' months');
        
        // Calculate total amount
        $monthlyRent = $property->price;
        $securityDeposit = $monthlyRent; // 1 month security deposit
        $advancePayment = $monthlyRent * 2; // 2 months advance
        $totalAmount = $securityDeposit + $advancePayment;

        $booking = Booking::create([
            'property_id' => $property->id,
            'user_id' => Auth::id(),
            'landlord_id' => $property->user_id,
            'start_date' => $data['start_date'],
            'end_date' => $endDate->format('Y-m-d'),
            'lease_duration_months' => $data['lease_duration_months'],
            'monthly_rent' => $monthlyRent,
            'security_deposit' => $securityDeposit,
            'advance_payment' => $advancePayment,
            'total_amount' => $totalAmount,
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking request submitted successfully! The landlord will review your request.');
    }

    /**
     * Display booking details
     */
    public function show(Booking $booking)
    {
        // Check authorization
        if ($booking->user_id !== Auth::id() && $booking->landlord_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $booking->load(['property.images', 'property.barangay', 'property.propertyType', 'tenant', 'landlord']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Only tenant or landlord can cancel
        if ($booking->user_id !== Auth::id() && $booking->landlord_id !== Auth::id()) {
            abort(403);
        }

        // Can only cancel pending or confirmed bookings
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'This booking cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }
}
