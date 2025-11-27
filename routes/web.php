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
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// PIN Setup Routes (for new users)
Route::middleware('auth')->group(function () {
    Route::get('/setup-pin', [AuthController::class, 'showSetupPin'])->name('setup-pin');
    Route::post('/setup-pin', [AuthController::class, 'storeSetupPin'])->name('store-setup-pin');
});

// PIN Verification Routes (for non-trusted devices)
Route::middleware('auth')->group(function () {
    Route::get('/verify-pin', [AuthController::class, 'showVerifyPin'])->name('verify-pin');
    Route::post('/verify-pin', [AuthController::class, 'verifyPin'])->name('verify-pin-submit');
});

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
    Route::resource('borrowers', BorrowerController::class)->only(['show','store','destroy']);

    // Loan Routes
    Route::resource('loans', LoanController::class)->only(['show','store']);

    // Payment Routes
    Route::resource('payments', PaymentController::class)->only(['store']);

    // API routes for AJAX calls
    Route::post('/api/borrowers', function(\Illuminate\Http\Request $request) {
        $user = Auth::user();

        // Check if phone number already exists
        $phone = $request->input('phone');
        if ($phone && \App\Models\Borrower::where('phone', $phone)->exists()) {
            return response()->json(['error' => 'Phone number already registered'], 409);
        }

        // Check if email already exists
        $email = $request->input('email');
        if ($email && \App\Models\Borrower::where('email', $email)->exists()) {
            return response()->json(['error' => 'Email already registered'], 409);
        }

        $borrower = $user->borrowers()->create([
            'first_name' => $request->input('fullName'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ]);

        return response()->json($borrower);
    });

    Route::post('/api/loans', function(\Illuminate\Http\Request $request) {
        $principal = $request->input('principal');
        $interest_rate = $request->input('interest_rate');
        $total_payable = $request->input('total_payable');

        $loan = \App\Models\Loan::create([
            'borrower_id' => $request->input('borrower_id'),
            'principal' => $principal,
            'balance' => $principal,  // Initialize balance to principal
            'total_due' => $total_payable,
            'term' => $request->input('term'),
            'interest_rate' => $interest_rate,
            'status' => 'active',
            'first_due_date' => now()->addDays($request->input('term')),
            'frequency' => $request->input('payment_frequency', 'monthly'),
        ]);

        return response()->json($loan);
    });

    Route::post('/api/payments', [PaymentController::class, 'store']);

    Route::get('/api/borrowers', function() {
        $user = Auth::user();
        $borrowers = $user->borrowers()->with(['loans' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->get();

        $borrowerData = $borrowers->map(function($borrower) {
            $totalLoanAmount = $borrower->loans->sum('principal');
            $totalPaid = $borrower->loans->sum(function($loan) {
                return $loan->payments->sum('amount');
            });

            return [
                'id' => $borrower->id,
                'name' => $borrower->fullName(),
                'amount' => $totalLoanAmount,
                'paidAmount' => $totalPaid,
                'days' => $borrower->loans->first()?->term ?? 0,
                'dueDate' => $borrower->loans->first()?->first_due_date?->toDateString(),
                'status' => 'active',
                'loanIds' => $borrower->loans->pluck('id')->toArray()
            ];
        });

        return response()->json($borrowerData);
    });

    Route::get('/api/loans/aging-details', function() {
        $user = Auth::user();
        $userBorrowers = $user->borrowers()->pluck('id');
        $loans = \App\Models\Loan::whereIn('borrower_id', $userBorrowers)->where('status', '!=', 'paid')->with('borrower')->get();
        return response()->json($loans);
    });

    Route::get('/api/recent-payments', function() {
        $user = Auth::user();
        $userBorrowers = $user->borrowers()->pluck('id');
        $payments = \App\Models\Payment::whereHas('loan', function($q) use ($userBorrowers) {
            $q->whereIn('borrower_id', $userBorrowers);
        })
        ->with('loan.borrower')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get()
        ->map(function($payment) {
            return [
                'id' => $payment->id,
                'borrower_name' => $payment->loan->borrower->fullName(),
                'loan_amount' => $payment->loan->principal,
                'amount' => $payment->amount,
                'created_at' => $payment->created_at
            ];
        });
        return response()->json($payments);
    });

    Route::get('/api/reports/aging', [ReportController::class,'agingReport'])->name('api.reports.aging');

    // Export Routes
    Route::get('/export/aging-report/excel', [ReportController::class,'exportAgingToExcel'])->name('export.aging.excel');
    Route::get('/export/aging-report/pdf', [ReportController::class,'exportAgingToPdf'])->name('export.aging.pdf');
    Route::get('/export/payments/excel', [ReportController::class,'exportPaymentsToExcel'])->name('export.payments.excel');
    Route::get('/export/payments/pdf', [ReportController::class,'exportPaymentsToPdf'])->name('export.payments.pdf');
    Route::get('/export/borrower/{borrower}/ledger/excel', [ReportController::class,'exportBorrowerLedgerToExcel'])->name('export.ledger.excel');
    Route::get('/export/borrower/{borrower}/ledger/pdf', [ReportController::class,'exportBorrowerLedgerToPdf'])->name('export.ledger.pdf');
});
