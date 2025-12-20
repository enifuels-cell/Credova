<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews for a property
     */
    public function index(Request $request, Property $property)
    {
        $reviews = $property->reviews()
            ->with(['guest', 'booking'])
            ->when($request->filled('verified'), function($query) {
                $query->verified();
            })
            ->when($request->filled('featured'), function($query) {
                $query->featured();
            })
            ->when($request->filled('with_photos'), function($query) {
                $query->withPhotos();
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json($reviews);
        }

        return view('reviews.index', compact('property', 'reviews'));
    }

    /**
     * Show the form for creating a new review
     */
    public function create(Booking $booking)
    {
        // Verify the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if booking is completed
        if ($booking->status !== 'completed') {
            return redirect()->back()->with('error', 'You can only review completed bookings.');
        }

        // Check if review already exists
        if ($booking->review) {
            return redirect()->back()->with('error', 'You have already reviewed this booking.');
        }

        return view('reviews.create', compact('booking'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, Booking $booking)
    {
        // Verify the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if booking is completed
        if ($booking->status !== 'completed') {
            return response()->json(['error' => 'You can only review completed bookings.'], 400);
        }

        // Check if review already exists
        if ($booking->review) {
            return response()->json(['error' => 'You have already reviewed this booking.'], 400);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'location_rating' => 'required|integer|min:1|max:5',
            'value_rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string|max:2000',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo uploads
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('review-photos', 'public');
                $photos[] = $path;
            }
        }

        // Create the review
        $review = Review::create([
            'property_id' => $booking->property_id,
            'booking_id' => $booking->id,
            'guest_id' => auth()->id(),
            'host_id' => $booking->property->user_id,
            'rating' => $request->rating,
            'cleanliness_rating' => $request->cleanliness_rating,
            'communication_rating' => $request->communication_rating,
            'location_rating' => $request->location_rating,
            'value_rating' => $request->value_rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'photos' => $photos,
            'is_verified' => true, // Auto-verify since it's from a completed booking
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully',
                'review' => $review->load(['guest', 'property']),
            ]);
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Thank you for your review!');
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        $review->load(['property', 'guest', 'booking']);
        
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review
     */
    public function edit(Review $review)
    {
        // Only allow guest to edit their own review within 7 days
        if ($review->guest_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($review->created_at->addDays(7)->isPast()) {
            return redirect()->back()->with('error', 'Reviews can only be edited within 7 days of submission.');
        }

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        // Only allow guest to edit their own review within 7 days
        if ($review->guest_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($review->created_at->addDays(7)->isPast()) {
            return response()->json(['error' => 'Reviews can only be edited within 7 days of submission.'], 400);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'location_rating' => 'required|integer|min:1|max:5',
            'value_rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string|max:2000',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo uploads
        $photos = $review->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('review-photos', 'public');
                $photos[] = $path;
            }
        }

        // Update the review
        $review->update([
            'rating' => $request->rating,
            'cleanliness_rating' => $request->cleanliness_rating,
            'communication_rating' => $request->communication_rating,
            'location_rating' => $request->location_rating,
            'value_rating' => $request->value_rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'photos' => $photos,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'review' => $review->fresh()->load(['guest', 'property']),
            ]);
        }

        return redirect()->route('reviews.show', $review)
            ->with('success', 'Review updated successfully');
    }

    /**
     * Add host response to a review
     */
    public function addResponse(Request $request, Review $review)
    {
        // Only allow property owner to respond
        if ($review->host_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if response already exists
        if ($review->host_response) {
            return response()->json(['error' => 'Response already exists for this review.'], 400);
        }

        $request->validate([
            'response' => 'required|string|max:1000',
        ]);

        $review->update([
            'host_response' => $request->response,
            'host_response_date' => now(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Response added successfully',
                'review' => $review->fresh(),
            ]);
        }

        return redirect()->back()->with('success', 'Response added successfully');
    }

    /**
     * Toggle featured status (admin only)
     */
    public function toggleFeatured(Review $review)
    {
        // This would need proper admin authorization
        // For now, we'll just allow property owners
        if ($review->host_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $review->update([
            'is_featured' => !$review->is_featured,
        ]);

        return response()->json([
            'success' => true,
            'is_featured' => $review->is_featured,
        ]);
    }

    /**
     * Get reviews statistics for a property
     */
    public function stats(Property $property)
    {
        $reviews = $property->reviews()->verified();
        
        $stats = [
            'total_reviews' => $reviews->count(),
            'average_rating' => $reviews->avg('rating'),
            'average_cleanliness' => $reviews->avg('cleanliness_rating'),
            'average_communication' => $reviews->avg('communication_rating'),
            'average_location' => $reviews->avg('location_rating'),
            'average_value' => $reviews->avg('value_rating'),
            'rating_distribution' => [
                5 => $reviews->where('rating', 5)->count(),
                4 => $reviews->where('rating', 4)->count(),
                3 => $reviews->where('rating', 3)->count(),
                2 => $reviews->where('rating', 2)->count(),
                1 => $reviews->where('rating', 1)->count(),
            ],
            'recent_reviews' => $reviews->recent(30)->count(),
            'reviews_with_photos' => $reviews->withPhotos()->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Remove the specified review (soft delete or admin action)
     */
    public function destroy(Review $review)
    {
        // Only allow guest or admin to delete
        if ($review->guest_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Delete associated photos
        if ($review->photos) {
            foreach ($review->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully',
        ]);
    }
}
