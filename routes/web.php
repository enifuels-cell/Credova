<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Landlord\DashboardController as LandlordDashboardController;
use App\Http\Controllers\Landlord\PropertyController as LandlordPropertyController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Properties
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('/property-type/{propertyType:slug}', [PropertyController::class, 'byType'])->name('properties.by-type');
Route::get('/barangay/{barangay}', [PropertyController::class, 'byBarangay'])->name('properties.by-barangay');

// Search with map (area/barangay search)
use App\Http\Controllers\SearchController;
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/api/barangay-autocomplete', [SearchController::class, 'barangayAutocomplete'])->name('api.barangay-autocomplete');
Route::get('/api/nearby-properties', [SearchController::class, 'nearbyProperties'])->name('api.nearby-properties');

// Inquiry (public can submit)
Route::post('/properties/{property}/inquiry', [InquiryController::class, 'store'])->name('inquiries.store');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/properties/{property}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // My Inquiries (for tenants)
    Route::get('/my-inquiries', [InquiryController::class, 'myInquiries'])->name('inquiries.my');

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isLandlord()) {
            return redirect()->route('landlord.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Landlord Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'landlord'])->prefix('landlord')->name('landlord.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [LandlordDashboardController::class, 'index'])->name('dashboard');

    // Properties CRUD
    Route::resource('properties', LandlordPropertyController::class);
    Route::delete('/properties/{property}/images/{image}', [LandlordPropertyController::class, 'deleteImage'])
        ->name('properties.images.delete');
    Route::post('/properties/{property}/images/{image}/primary', [LandlordPropertyController::class, 'setPrimaryImage'])
        ->name('properties.images.primary');

    // Inquiries
    Route::get('/inquiries', [InquiryController::class, 'landlordInquiries'])->name('inquiries.index');
    Route::put('/inquiries/{inquiry}', [InquiryController::class, 'updateStatus'])->name('inquiries.update');
    Route::patch('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('inquiries.status');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Properties Management
    Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/{property}', [AdminPropertyController::class, 'show'])->name('properties.show');
    Route::patch('/properties/{property}/status', [AdminPropertyController::class, 'updateStatus'])->name('properties.status');
    Route::post('/properties/{property}/featured', [AdminPropertyController::class, 'toggleFeatured'])->name('properties.featured');
    Route::post('/properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('properties.approve');
    Route::post('/properties/{property}/feature', [AdminPropertyController::class, 'toggleFeatured'])->name('properties.feature');
    Route::delete('/properties/{property}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');

    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
    Route::post('/users/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('users.status');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
