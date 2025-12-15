<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display all reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['property', 'user']);

        // Filter by approval status
        if ($request->filled('is_approved')) {
            $query->where('is_approved', $request->is_approved);
        }

        $reviews = $query->latest()->paginate(15);

        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('is_approved', true)->count(),
            'pending' => Review::where('is_approved', false)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Approve a review
     */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Review approved successfully.');
    }

    /**
     * Reject/unapprove a review
     */
    public function unapprove(Review $review)
    {
        $review->update(['is_approved' => false]);

        return redirect()->back()->with('success', 'Review unapproved successfully.');
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}
