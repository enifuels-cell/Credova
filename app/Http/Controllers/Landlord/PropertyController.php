<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Barangay;
use App\Models\Amenity;
use App\Models\PropertyImage;
use App\Http\Requests\PropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Display landlord's properties
     */
    public function index()
    {
        $properties = Property::with(['propertyType', 'barangay', 'images'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('landlord.properties.index', compact('properties'));
    }

    /**
     * Show create property form
     */
    public function create()
    {
        $propertyTypes = PropertyType::active()->get();
        $barangays = Barangay::active()->orderBy('name')->get();
        $amenities = Amenity::active()->get()->groupBy('category');

        return view('landlord.properties.create', compact('propertyTypes', 'barangays', 'amenities'));
    }

    /**
     * Store new property
     */
    public function store(PropertyRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending'; // Pending admin approval

        $property = Property::create($data);

        // Attach amenities
        if ($request->has('amenities')) {
            $property->amenities()->attach($request->amenities);
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Property listing created successfully! It will be visible after admin approval.');
    }

    /**
     * Show edit property form
     */
    public function edit(Property $property)
    {
        // Ensure user owns this property
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $property->load(['images', 'amenities']);
        $propertyTypes = PropertyType::active()->get();
        $barangays = Barangay::active()->orderBy('name')->get();
        $amenities = Amenity::active()->get()->groupBy('category');

        return view('landlord.properties.edit', compact('property', 'propertyTypes', 'barangays', 'amenities'));
    }

    /**
     * Update property
     */
    public function update(PropertyRequest $request, Property $property)
    {
        // Ensure user owns this property
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validated();
        $property->update($data);

        // Sync amenities
        if ($request->has('amenities')) {
            $property->amenities()->sync($request->amenities);
        } else {
            $property->amenities()->detach();
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $existingCount = $property->images()->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'sort_order' => $existingCount + $index,
                    'is_primary' => $existingCount === 0 && $index === 0,
                ]);
            }
        }

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Property listing updated successfully!');
    }

    /**
     * Delete property
     */
    public function destroy(Property $property)
    {
        // Ensure user owns this property
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete images from storage
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $property->delete();

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Property listing deleted successfully!');
    }

    /**
     * Delete property image
     */
    public function deleteImage(Property $property, PropertyImage $image)
    {
        // Ensure user owns this property
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        // If deleted image was primary, set another as primary
        if ($image->is_primary) {
            $firstImage = $property->images()->first();
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
            }
        }

        return redirect()->back()->with('success', 'Image deleted successfully!');
    }

    /**
     * Set primary image
     */
    public function setPrimaryImage(Property $property, PropertyImage $image)
    {
        // Ensure user owns this property
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        // Unset all primary images
        $property->images()->update(['is_primary' => false]);

        // Set new primary
        $image->update(['is_primary' => true]);

        return redirect()->back()->with('success', 'Primary image updated!');
    }
}
