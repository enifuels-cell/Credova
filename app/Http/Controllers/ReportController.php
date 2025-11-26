<?php
namespace App\Http\Controllers;
use App\Models\Loan;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AgingReportExport;
use App\Exports\BorrowerLedgerExport;
use App\Exports\PaymentHistoryExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // Aging report - buckets: current, 1-30, 31-60, 61-90, >90
    public function agingReport(Request $r)
    {
        $today = now()->toDateString();

        // We'll assume loans have a 'first_due_date' and balance.
        $buckets = [
            'current' => "SUM(CASE WHEN DATEDIFF('$today', first_due_date) <= 0 THEN balance ELSE 0 END) as current",
            '1_30' => "SUM(CASE WHEN DATEDIFF('$today', first_due_date) BETWEEN 1 AND 30 THEN balance ELSE 0 END) as `1_30`",
            '31_60' => "SUM(CASE WHEN DATEDIFF('$today', first_due_date) BETWEEN 31 AND 60 THEN balance ELSE 0 END) as `31_60`",
            '61_90' => "SUM(CASE WHEN DATEDIFF('$today', first_due_date) BETWEEN 61 AND 90 THEN balance ELSE 0 END) as `61_90`",
            'gt_90' => "SUM(CASE WHEN DATEDIFF('$today', first_due_date) > 90 THEN balance ELSE 0 END) as `gt_90`",
            'total' => "SUM(balance) as total"
        ];

        $select = implode(', ', $buckets);
        $data = DB::table('loans')->selectRaw($select)->where('status','!=','paid')->first();

        // Always return JSON for this route (it's called via AJAX from the view)
        return response()->json($data);
    }

    // ledger by borrower: show loans and payments in chronological order with running balances
    public function ledgerByBorrower($borrowerId)
    {
        $borrower = Borrower::with(['loans','payments'])->findOrFail($borrowerId);

        // build per loan ledger
        $ledgers = [];
        foreach ($borrower->loans as $loan) {
            $balance = $loan->total_due;
            $rows = [];
            $rows[] = [
                'date' => $loan->issued_at ? $loan->issued_at->toDateString() : null,
                'type' => 'issued',
                'amount' => $loan->total_due,
                'balance' => $balance
            ];
            foreach ($loan->payments()->orderBy('paid_at')->get() as $p) {
                $balance = round($balance - $p->amount, 2);
                $rows[] = [
                    'date' => $p->paid_at->toDateString(),
                    'type' => 'payment',
                    'amount' => $p->amount,
                    'balance' => $balance,
                ];
            }
            $ledgers[] = [
                'loan_id' => $loan->id,
                'loan' => $loan,
                'entries' => $rows,
                'current_balance' => $loan->balance
            ];
        }

        return response()->json([
            'borrower' => $borrower,
            'ledgers' => $ledgers
        ]);
    }

    // Excel Exports
    public function exportAgingToExcel()
    {
        $filename = 'aging-report-' . now()->format('Y-m-d-His') . '.xlsx';
        return Excel::download(new AgingReportExport(null), $filename);
    }

    public function exportAgingToPdf()
    {
        $user = Auth::user();
        $userBorrowers = $user->borrowers()->pluck('id');
        $loans = Loan::whereIn('borrower_id', $userBorrowers)
            ->where('status', '!=', 'paid')
            ->with('borrower')
            ->get();

        $html = view('exports.aging-report-pdf', ['loans' => $loans])->render();
        $pdf = Pdf::loadHTML($html);
        $filename = 'aging-report-' . now()->format('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportPaymentsToExcel()
    {
        $filename = 'payment-history-' . now()->format('Y-m-d-His') . '.xlsx';
        return Excel::download(new PaymentHistoryExport(), $filename);
    }

    public function exportPaymentsToPdf()
    {
        $user = Auth::user();
        $userBorrowers = $user->borrowers()->pluck('id');
        $payments = \App\Models\Payment::whereHas('loan', function($q) use ($userBorrowers) {
            $q->whereIn('borrower_id', $userBorrowers);
        })
        ->with('loan.borrower')
        ->orderBy('paid_at', 'desc')
        ->get();

        $html = view('exports.payment-history-pdf', ['payments' => $payments])->render();
        $pdf = Pdf::loadHTML($html);
        $filename = 'payment-history-' . now()->format('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportBorrowerLedgerToExcel($borrowerId)
    {
        $borrower = Borrower::findOrFail($borrowerId);
        $filename = 'ledger-' . $borrower->fullName() . '-' . now()->format('Y-m-d-His') . '.xlsx';
        return Excel::download(new BorrowerLedgerExport($borrower), $filename);
    }

    public function exportBorrowerLedgerToPdf($borrowerId)
    {
        $borrower = Borrower::with(['loans.payments'])->findOrFail($borrowerId);

        // Build ledger data
        $ledgers = [];
        foreach ($borrower->loans as $loan) {
            $balance = (float)$loan->total_due;
            $rows = [];
            $rows[] = [
                'date' => $loan->issued_at ? $loan->issued_at->toDateString() : 'N/A',
                'type' => 'Loan Issued',
                'amount' => (float)$loan->total_due,
                'balance' => $balance
            ];
            foreach ($loan->payments()->orderBy('paid_at')->get() as $p) {
                $balance = round($balance - (float)$p->amount, 2);
                $rows[] = [
                    'date' => $p->paid_at->toDateString(),
                    'type' => 'Payment',
                    'amount' => (float)$p->amount,
                    'balance' => $balance,
                ];
            }
            $ledgers[] = [
                'loan_id' => $loan->id,
                'loan' => $loan,
                'entries' => $rows,
                'current_balance' => (float)$loan->balance
            ];
        }

        $html = view('exports.borrower-ledger-pdf', [
            'borrower' => $borrower,
            'ledgers' => $ledgers
        ])->render();
        $pdf = Pdf::loadHTML($html);
        $filename = 'ledger-' . $borrower->fullName() . '-' . now()->format('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }
}
