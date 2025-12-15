<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Property;
use App\Models\Booking;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(ReviewRequest $request, Property $property)
    {
        // Check if user has completed booking for this property
        $hasCompletedBooking = Booking::where('property_id', $property->id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->exists();

        if (!$hasCompletedBooking) {
            return redirect()->back()->with('error', 'You can only review properties you have booked and completed.');
        }

        // Check if user already reviewed this property
        $existingReview = Review::where('property_id', $property->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this property.');
        }

        $data = $request->validated();
        $data['property_id'] = $property->id;
        $data['user_id'] = Auth::id();
        $data['is_approved'] = false; // Requires admin approval

        // Get the most recent completed booking
        $booking = Booking::where('property_id', $property->id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest()
            ->first();

        if ($booking) {
            $data['booking_id'] = $booking->id;
        }

        Review::create($data);

        return redirect()->back()->with('success', 'Review submitted successfully! It will be visible after admin approval.');
    }

    /**
     * Display user's reviews
     */
    public function index()
    {
        $reviews = Review::with(['property.images', 'property.barangay'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('reviews.index', compact('reviews'));
    }
}
