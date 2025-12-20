<?php

namespace App\Http\Controllers;

use App\Models\SavedSearch;
use App\Models\Property;
use Illuminate\Http\Request;

class SavedSearchController extends Controller
{
    /**
     * Display a listing of user's saved searches
     */
    public function index()
    {
        $savedSearches = auth()->user()->savedSearches()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($search) {
                $search->results_count = $search->executeSearch()->count();
                return $search;
            });

        return view('saved-searches.index', compact('savedSearches'));
    }

    /**
     * Show the form for creating a new saved search
     */
    public function create(Request $request)
    {
        // Get current search parameters from query string
        $searchCriteria = $request->only([
            'location', 'min_price', 'max_price', 'guests', 
            'bedrooms', 'bathrooms', 'property_type', 'amenities',
            'check_in', 'check_out'
        ]);

        return view('saved-searches.create', compact('searchCriteria'));
    }

    /**
     * Store a newly created saved search
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email_alerts' => 'boolean',
            'alert_frequency' => 'in:daily,weekly,monthly',
            'search_criteria' => 'required|array',
        ]);

        // Limit number of saved searches per user
        if (auth()->user()->savedSearches()->count() >= 10) {
            return response()->json([
                'error' => 'You can only have up to 10 saved searches. Please delete some existing searches first.'
            ], 400);
        }

        $savedSearch = auth()->user()->savedSearches()->create([
            'name' => $request->name,
            'search_criteria' => $request->search_criteria,
            'email_alerts' => $request->boolean('email_alerts'),
            'alert_frequency' => $request->email_alerts ? ($request->alert_frequency ?? 'weekly') : null,
            'is_active' => true,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Search saved successfully',
                'saved_search' => $savedSearch,
            ]);
        }

        return redirect()->route('saved-searches.index')
            ->with('success', 'Search saved successfully');
    }

    /**
     * Display the specified saved search and its results
     */
    public function show(SavedSearch $savedSearch)
    {
        // Verify ownership
        if ($savedSearch->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $properties = $savedSearch->executeSearch();
        
        return view('saved-searches.show', compact('savedSearch', 'properties'));
    }

    /**
     * Show the form for editing the specified saved search
     */
    public function edit(SavedSearch $savedSearch)
    {
        // Verify ownership
        if ($savedSearch->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('saved-searches.edit', compact('savedSearch'));
    }

    /**
     * Update the specified saved search
     */
    public function update(Request $request, SavedSearch $savedSearch)
    {
        // Verify ownership
        if ($savedSearch->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email_alerts' => 'boolean',
            'alert_frequency' => 'in:daily,weekly,monthly',
            'search_criteria' => 'required|array',
            'is_active' => 'boolean',
        ]);

        $savedSearch->update([
            'name' => $request->name,
            'search_criteria' => $request->search_criteria,
            'email_alerts' => $request->boolean('email_alerts'),
            'alert_frequency' => $request->email_alerts ? ($request->alert_frequency ?? 'weekly') : null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Search updated successfully',
                'saved_search' => $savedSearch->fresh(),
            ]);
        }

        return redirect()->route('saved-searches.index')
            ->with('success', 'Search updated successfully');
    }

    /**
     * Toggle active status of a saved search
     */
    public function toggleActive(SavedSearch $savedSearch)
    {
        // Verify ownership
        if ($savedSearch->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $savedSearch->update([
            'is_active' => !$savedSearch->is_active,
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $savedSearch->is_active,
            'message' => $savedSearch->is_active ? 'Search activated' : 'Search deactivated',
        ]);
    }

    /**
     * Get fresh results for a saved search
     */
    public function results(SavedSearch $savedSearch)
    {
        // Verify ownership
        if ($savedSearch->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $properties = $savedSearch->executeSearch();
        
        return response()->json([
            'properties' => $properties->load(['user', 'images']),
            'count' => $properties->count(),
            'search_summary' => $savedSearch->search_summary,
        ]);
    }

    /**
     * Send test alert for a saved search
     */
    public function sendTestAlert(SavedSearch $savedSearch)
    {
        // Verify ownership
        if ($savedSearch->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$savedSearch->email_alerts || !$savedSearch->is_active) {
            return response()->json([
                'error' => 'Email alerts are not enabled for this search'
            ], 400);
        }

        $properties = $savedSearch->executeSearch();
        
        // Here you would send the email
        // For now, we'll just return the data that would be sent
        
        return response()->json([
            'success' => true,
            'message' => 'Test alert would be sent',
            'properties_count' => $properties->count(),
            'search_name' => $savedSearch->name,
        ]);
    }

    /**
     * Remove the specified saved search
     */
    public function destroy(SavedSearch $savedSearch)
    {
        // Verify ownership
        if ($savedSearch->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $savedSearch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Saved search deleted successfully',
        ]);
    }

    /**
     * Bulk operations on saved searches
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,disable_alerts',
            'search_ids' => 'required|array',
            'search_ids.*' => 'exists:saved_searches,id',
        ]);

        $savedSearches = SavedSearch::whereIn('id', $request->search_ids)
            ->where('user_id', auth()->id())
            ->get();

        if ($savedSearches->isEmpty()) {
            return response()->json(['error' => 'No valid searches found'], 400);
        }

        $count = 0;
        foreach ($savedSearches as $search) {
            switch ($request->action) {
                case 'delete':
                    $search->delete();
                    $count++;
                    break;
                case 'activate':
                    $search->update(['is_active' => true]);
                    $count++;
                    break;
                case 'deactivate':
                    $search->update(['is_active' => false]);
                    $count++;
                    break;
                case 'disable_alerts':
                    $search->update(['email_alerts' => false]);
                    $count++;
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} searches updated successfully",
        ]);
    }

    /**
     * Save current search from property listing page
     */
    public function saveFromSearch(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        // Parse URL to extract search criteria
        $urlParts = parse_url($request->url);
        parse_str($urlParts['query'] ?? '', $searchCriteria);

        // Clean up and validate search criteria
        $validCriteria = array_intersect_key($searchCriteria, array_flip([
            'location', 'min_price', 'max_price', 'guests', 
            'bedrooms', 'bathrooms', 'property_type', 'amenities',
            'check_in', 'check_out'
        ]));

        if (empty($validCriteria)) {
            return response()->json([
                'error' => 'No valid search criteria found in URL'
            ], 400);
        }

        $savedSearch = auth()->user()->savedSearches()->create([
            'name' => $request->name,
            'search_criteria' => $validCriteria,
            'email_alerts' => false,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Search saved successfully',
            'saved_search' => $savedSearch,
        ]);
    }
}
