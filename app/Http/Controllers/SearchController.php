<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Barangay;
use App\Models\PropertyType;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle search with map display for area searches
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $searchType = $this->detectSearchType($query);

        // Get properties based on search
        $propertiesQuery = Property::with(['propertyType', 'barangay', 'images', 'landlord'])
            ->active();

        $showMap = false;
        $mapCenter = ['lat' => 8.4780, 'lng' => 124.6400]; // Default CDO center
        $mapZoom = 13;
        $areaName = '';
        $matchedBarangays = collect();

        if (!empty($query)) {
            $searchLower = strtolower($query);

            // Check if searching for Uptown
            if (str_contains($searchLower, 'uptown')) {
                $showMap = true;
                $areaName = 'Uptown CDO';
                $matchedBarangays = Barangay::where('area_type', 'uptown')->get();
                $propertiesQuery->whereHas('barangay', function ($q) {
                    $q->where('area_type', 'uptown');
                })->orWhere('address', 'like', '%uptown%');
                $mapCenter = ['lat' => 8.4853, 'lng' => 124.6467];
                $mapZoom = 14;
            }
            // Check if searching for Downtown
            elseif (str_contains($searchLower, 'downtown') || str_contains($searchLower, 'centro') || str_contains($searchLower, 'divisoria')) {
                $showMap = true;
                $areaName = 'Downtown CDO';
                $matchedBarangays = Barangay::where('area_type', 'downtown')->get();
                $propertiesQuery->whereHas('barangay', function ($q) {
                    $q->where('area_type', 'downtown');
                })->orWhere('address', 'like', '%downtown%');
                $mapCenter = ['lat' => 8.4806, 'lng' => 124.6455];
                $mapZoom = 14;
            }
            // Check if searching for a specific barangay
            elseif ($barangay = $this->findBarangay($query)) {
                $showMap = true;
                $areaName = 'Barangay ' . $barangay->name;
                $matchedBarangays = collect([$barangay]);
                $propertiesQuery->where('barangay_id', $barangay->id);
                if ($barangay->latitude && $barangay->longitude) {
                    $mapCenter = ['lat' => (float)$barangay->latitude, 'lng' => (float)$barangay->longitude];
                }
                $mapZoom = 15;
            }
            // Check if searching by area type
            elseif (str_contains($searchLower, 'suburban')) {
                $showMap = true;
                $areaName = 'Suburban Areas';
                $matchedBarangays = Barangay::where('area_type', 'suburban')->get();
                $propertiesQuery->whereHas('barangay', function ($q) {
                    $q->where('area_type', 'suburban');
                });
            }
            elseif (str_contains($searchLower, 'rural')) {
                $showMap = true;
                $areaName = 'Rural Areas';
                $matchedBarangays = Barangay::where('area_type', 'rural')->get();
                $propertiesQuery->whereHas('barangay', function ($q) {
                    $q->where('area_type', 'rural');
                });
            }
            // Regular property search (no map needed)
            else {
                $propertiesQuery->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('address', 'like', "%{$query}%");
                });
                $searchType = 'property';
            }
        }

        $properties = $propertiesQuery->paginate(12)->withQueryString();
        $propertyTypes = PropertyType::active()->get();
        $barangays = Barangay::active()->orderBy('name')->get();

        // Prepare properties for map markers (only properties with coordinates)
        $mapProperties = $properties->filter(function ($property) {
            return $property->latitude && $property->longitude;
        })->map(function ($property) {
            return [
                'id' => $property->id,
                'title' => $property->title,
                'slug' => $property->slug,
                'lat' => (float)$property->latitude,
                'lng' => (float)$property->longitude,
                'price' => number_format($property->price),
                'type' => $property->propertyType->name ?? 'Property',
                'barangay' => $property->barangay->name ?? '',
                'image' => $property->images->first() ? asset('storage/' . $property->images->first()->image_path) : 'https://via.placeholder.com/150?text=🏠',
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
            ];
        })->values();

        return view('search.results', compact(
            'properties',
            'propertyTypes',
            'barangays',
            'query',
            'searchType',
            'showMap',
            'mapCenter',
            'mapZoom',
            'mapProperties',
            'areaName',
            'matchedBarangays'
        ));
    }

    /**
     * Detect what type of search the user is performing
     */
    private function detectSearchType(string $query): string
    {
        $queryLower = strtolower($query);

        // Area searches
        if (str_contains($queryLower, 'uptown') ||
            str_contains($queryLower, 'downtown') ||
            str_contains($queryLower, 'centro') ||
            str_contains($queryLower, 'divisoria') ||
            str_contains($queryLower, 'suburban') ||
            str_contains($queryLower, 'rural')) {
            return 'area';
        }

        // Barangay search
        if (str_contains($queryLower, 'barangay') || str_contains($queryLower, 'brgy')) {
            return 'barangay';
        }

        // Check if matches a barangay name
        $barangayMatch = Barangay::where('name', 'like', "%{$query}%")->first();
        if ($barangayMatch) {
            return 'barangay';
        }

        return 'property';
    }

    /**
     * Find a barangay by name
     */
    private function findBarangay(string $query): ?Barangay
    {
        $queryLower = strtolower($query);

        // Remove common prefixes
        $cleaned = str_replace(['barangay ', 'brgy ', 'brgy. '], '', $queryLower);

        return Barangay::where(function ($q) use ($query, $cleaned) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('name', 'like', "%{$cleaned}%");
        })->first();
    }

    /**
     * API endpoint for getting properties near a location
     */
    public function nearbyProperties(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $radius = $request->get('radius', 2); // Default 2km radius

        if (!$lat || !$lng) {
            return response()->json(['error' => 'Latitude and longitude required'], 400);
        }

        // Haversine formula to calculate distance in km
        $properties = Property::with(['propertyType', 'barangay', 'images'])
            ->active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("*, (
                6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();

        return response()->json([
            'properties' => $properties->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'slug' => $property->slug,
                    'lat' => (float)$property->latitude,
                    'lng' => (float)$property->longitude,
                    'distance' => round($property->distance, 2),
                    'price' => number_format($property->price),
                    'type' => $property->propertyType->name ?? 'Property',
                    'barangay' => $property->barangay->name ?? '',
                    'image' => $property->images->first() ? asset('storage/' . $property->images->first()->image_path) : null,
                ];
            }),
        ]);
    }

    /**
     * Get barangays for autocomplete
     */
    public function barangayAutocomplete(Request $request)
    {
        $query = $request->get('q', '');

        $barangays = Barangay::active()
            ->where('name', 'like', "%{$query}%")
            ->withCoordinates()
            ->limit(10)
            ->get()
            ->map(function ($barangay) {
                return [
                    'id' => $barangay->id,
                    'name' => $barangay->name,
                    'area_type' => $barangay->area_type,
                    'area_label' => $barangay->area_type_label,
                    'lat' => (float)$barangay->latitude,
                    'lng' => (float)$barangay->longitude,
                ];
            });

        return response()->json($barangays);
    }
}
