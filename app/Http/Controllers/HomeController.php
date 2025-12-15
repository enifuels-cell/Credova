<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Barangay;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index()
    {
        $featuredProperties = Property::with(['propertyType', 'barangay', 'images'])
            ->active()
            ->featured()
            ->latest()
            ->take(4)
            ->get();

        $latestProperties = Property::with(['propertyType', 'barangay', 'images'])
            ->active()
            ->latest()
            ->take(4)
            ->get();

        $rentalTypes = PropertyType::active()
            ->where('category', 'rental')
            ->withCount(['properties' => function ($query) {
                $query->active();
            }])
            ->get();

        $staysTypes = PropertyType::active()
            ->where('category', 'stays')
            ->withCount(['properties' => function ($query) {
                $query->active();
            }])
            ->get();

        $eventTypes = PropertyType::active()
            ->where('category', 'events')
            ->withCount(['properties' => function ($query) {
                $query->active();
            }])
            ->get();

        $propertyTypes = PropertyType::active()
            ->withCount(['properties' => function ($query) {
                $query->active();
            }])
            ->get();

        $popularBarangays = Barangay::active()
            ->withCount(['properties' => function ($query) {
                $query->active();
            }])
            ->orderByDesc('properties_count')
            ->take(6)
            ->get();

        return view('welcome', compact(
            'featuredProperties',
            'latestProperties',
            'propertyTypes',
            'rentalTypes',
            'staysTypes',
            'eventTypes',
            'popularBarangays'
        ));
    }
}
