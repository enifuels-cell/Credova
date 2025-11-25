<?php
namespace App\Http\Controllers;
use App\Models\Loan;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
