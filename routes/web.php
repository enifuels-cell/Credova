<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public FAQ Route
Route::get('/faq', function() {
    return view('faq');
})->name('faq');

// Public Privacy Policy Route
Route::get('/privacy', function() {
    return view('privacy');
})->name('privacy');

// Public Terms of Service Route
Route::get('/terms', function() {
    return view('terms');
})->name('terms');

// Public Cookie Policy Route
Route::get('/cookies', function() {
    return view('cookies');
})->name('cookies');

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Redirect root to dashboard if authenticated
    Route::get('/', function(){
        return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
    });

    // Borrower Routes
    Route::resource('borrowers', BorrowerController::class)->only(['index','show','store']);

    // Loan Routes
    Route::resource('loans', LoanController::class)->only(['show','store']);

    // Payment Routes
    Route::resource('payments', PaymentController::class)->only(['store']);

    // Report Routes
    Route::get('reports/aging', function() {
        return view('reports.aging');
    })->name('reports.aging');
    Route::get('reports/ledger/{borrower}', [ReportController::class,'ledgerByBorrower'])->name('reports.ledger');

    // API routes for AJAX calls
    Route::get('/api/loans/aging-details', function() {
        $loans = \App\Models\Loan::where('status', '!=', 'paid')->with('borrower')->get();
        return response()->json($loans);
    });

    Route::get('/api/reports/aging', [ReportController::class,'agingReport'])->name('api.reports.aging');
});
