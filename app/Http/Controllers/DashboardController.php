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
        // Total capital released (principal of all loans)
        $total_capital_released = Loan::sum('principal');

        // Total interest earned (total_due - principal for all loans)
        $total_interest_earned = Loan::selectRaw('SUM(total_due - principal) as total_interest')
            ->first()
            ->total_interest ?? 0;

        // Calculate ROI percentage
        $roi_percentage = ($total_capital_released > 0)
            ? (($total_interest_earned / $total_capital_released) * 100)
            : 0;

        // Break-even status (check if interest earned covers a threshold or target)
        $break_even_status = $total_interest_earned > 0 ? 'Profitable' : 'Not Yet';

        $stats = [
            'total_borrowers' => Borrower::count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'total_outstanding' => Loan::where('status', 'active')->sum('balance'),
            'total_collected' => Payment::sum('amount'),
            'total_capital_released' => $total_capital_released,
            'total_interest_earned' => $total_interest_earned,
            'roi_percentage' => round($roi_percentage, 2),
            'break_even_status' => $break_even_status,
        ];

        $recent_payments = Payment::with('loan.borrower')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $overdue_loans = Loan::where('status', '!=', 'paid')
            ->where('first_due_date', '<', now())
            ->with('borrower')
            ->count();

        return view('dashboard.admin', compact('stats', 'recent_payments', 'overdue_loans'));
    }
}
