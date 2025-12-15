<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Barangay;
use App\Models\Amenity;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display property listing with search/filter
     */
    public function index(Request $request)
    {
        $query = Property::with(['propertyType', 'barangay', 'images', 'landlord'])
            ->active();

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by property type
        if ($request->filled('property_type')) {
            $query->where('property_type_id', $request->property_type);
        }

        // Filter by barangay
        if ($request->filled('barangay')) {
            $query->where('barangay_id', $request->barangay);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by bedrooms
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        // Filter by bathrooms
        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }

        // Filter by furnished
        if ($request->filled('furnished')) {
            $query->where('is_furnished', $request->furnished == '1');
        }

        // Filter by pets allowed
        if ($request->filled('pets_allowed')) {
            $query->where('pets_allowed', $request->pets_allowed == '1');
        }

        // Filter by parking
        if ($request->filled('parking')) {
            $query->where('parking_available', $request->parking == '1');
        }

        // Filter by amenities
        if ($request->filled('amenities')) {
            $amenityIds = $request->amenities;
            $query->whereHas('amenities', function ($q) use ($amenityIds) {
                $q->whereIn('amenities.id', $amenityIds);
            });
        }

        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $properties = $query->paginate(12)->withQueryString();
        $propertyTypes = PropertyType::active()->get();
        $barangays = Barangay::active()->orderBy('name')->get();
        $amenities = Amenity::active()->get();

        return view('properties.index', compact(
            'properties',
            'propertyTypes',
            'barangays',
            'amenities'
        ));
    }

    /**
     * Display single property details
     */
    public function show(Property $property)
    {
        // Only show active properties to public
        if ($property->status !== 'active') {
            abort(404);
        }

        $property->load([
            'propertyType',
            'barangay',
            'images',
            'amenities',
            'landlord',
            'reviews' => function ($query) {
                $query->approved()->with('user')->latest();
            }
        ]);

        // Increment view count
        $property->incrementViews();

        // Get similar properties
        $similarProperties = Property::with(['propertyType', 'barangay', 'images'])
            ->active()
            ->where('id', '!=', $property->id)
            ->where(function ($query) use ($property) {
                $query->where('property_type_id', $property->property_type_id)
                    ->orWhere('barangay_id', $property->barangay_id);
            })
            ->take(4)
            ->get();

        return view('properties.show', compact('property', 'similarProperties'));
    }

    /**
     * Display properties by type
     */
    public function byType(PropertyType $propertyType)
    {
        $properties = Property::with(['propertyType', 'barangay', 'images'])
            ->active()
            ->where('property_type_id', $propertyType->id)
            ->latest()
            ->paginate(12);

        return view('properties.by-type', compact('properties', 'propertyType'));
    }

    /**
     * Display properties by barangay
     */
    public function byBarangay(Barangay $barangay)
    {
        $properties = Property::with(['propertyType', 'barangay', 'images'])
            ->active()
            ->where('barangay_id', $barangay->id)
            ->latest()
            ->paginate(12);

        return view('properties.by-barangay', compact('properties', 'barangay'));
    }
}
