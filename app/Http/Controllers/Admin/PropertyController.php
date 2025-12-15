<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display all properties
     */
    public function index(Request $request)
    {
        $query = Property::with(['user', 'propertyType', 'barangay', 'images']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by approval status
        if ($request->filled('is_approved')) {
            $query->where('is_approved', $request->is_approved);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $properties = $query->latest()->paginate(15);

        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Show property details
     */
    public function show(Property $property)
    {
        $property->load(['user', 'propertyType', 'barangay', 'images', 'amenities']);

        return view('admin.properties.show', compact('property'));
    }

    /**
     * Update property status
     */
    public function updateStatus(Request $request, Property $property)
    {
        $request->validate([
            'status' => 'required|in:available,rented,pending,maintenance',
        ]);

        $property->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Property status updated successfully.');
    }

    /**
     * Approve a property
     */
    public function approve(Property $property)
    {
        $property->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Property approved successfully.');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Property $property)
    {
        $property->update(['is_featured' => !$property->is_featured]);

        $message = $property->is_featured
            ? 'Property marked as featured.'
            : 'Property removed from featured.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete property
     */
    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}
