<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite status
     */
    public function toggle(Property $property)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('property_id', $property->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Property removed from favorites.';
            $isFavorited = false;
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'property_id' => $property->id,
            ]);
            $message = 'Property added to favorites.';
            $isFavorited = true;
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorited' => $isFavorited,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Display user's favorites
     */
    public function index()
    {
        $favorites = Favorite::with(['property.propertyType', 'property.barangay', 'property.images'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }
}
