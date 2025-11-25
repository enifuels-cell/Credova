<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard data for authenticated user
        return $this->userDashboard();
    }

    private function userDashboard()
    {
        $user = Auth::user();

        // Get borrowers for this user only
        $userBorrowers = $user->borrowers()->pluck('id');

        // Total capital released (principal of all loans for this user)
        $total_capital_released = Loan::whereIn('borrower_id', $userBorrowers)->sum('principal');

        // Total interest earned (total_due - principal for all loans for this user)
        $total_interest_earned = Loan::whereIn('borrower_id', $userBorrowers)
            ->selectRaw('SUM(total_due - principal) as total_interest')
            ->first()
            ->total_interest ?? 0;

        // Calculate ROI percentage
        $roi_percentage = ($total_capital_released > 0)
            ? (($total_interest_earned / $total_capital_released) * 100)
            : 0;

        // Break-even status (check if interest earned covers a threshold or target)
        $break_even_status = $total_interest_earned > 0 ? 'Profitable' : 'Not Yet';

        $stats = [
            'total_borrowers' => $user->borrowers()->count(),
            'active_loans' => Loan::whereIn('borrower_id', $userBorrowers)->where('status', 'active')->count(),
            'total_outstanding' => Loan::whereIn('borrower_id', $userBorrowers)->where('status', 'active')->sum('balance'),
            'total_collected' => Payment::whereHas('loan', function($q) use ($userBorrowers) {
                $q->whereIn('borrower_id', $userBorrowers);
            })->sum('amount'),
            'total_capital_released' => $total_capital_released,
            'total_interest_earned' => $total_interest_earned,
            'roi_percentage' => round($roi_percentage, 2),
            'break_even_status' => $break_even_status,
        ];

        $recent_payments = Payment::whereHas('loan', function($q) use ($userBorrowers) {
            $q->whereIn('borrower_id', $userBorrowers);
        })
            ->with('loan.borrower')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $overdue_loans = Loan::whereIn('borrower_id', $userBorrowers)
            ->where('status', '!=', 'paid')
            ->where('first_due_date', '<', now())
            ->count();

        return view('dashboard.admin', compact('stats', 'recent_payments', 'overdue_loans'));
    }
}
