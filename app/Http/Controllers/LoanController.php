<?php
namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    // Store a new loan
    public function store(Request $r)
    {
        $data = $r->validate([
            'borrower_id'=>'required|exists:borrowers,id',
            'principal'=>'required|numeric',
            'interest_rate'=>'nullable|numeric',
            'issued_at'=>'nullable|date',
            'first_due_date'=>'nullable|date',
            'term'=>'nullable|integer',
            'frequency'=>'nullable|string',
        ]);

        $principal = $data['principal'];
        $interest = ($data['interest_rate'] ?? 0);

        $total_due = round($principal + ($principal * ($interest / 100)), 2);

        $loan = Loan::create(array_merge($data, [
            'total_due' => $total_due,
            'balance' => $total_due,
            'status'=>'active'
        ]));

        return redirect()->route('loans.show', $loan->id)->with('success', 'Loan created successfully!');
    }

    // Show a single loan with ledger
    public function show(Loan $loan)
    {
        $loan->load(['borrower','payments','collectionAttempts.collector']);

        $ledger = [];
        $balance = $loan->total_due;
        $ledger[] = [
            'date' => $loan->issued_at ? $loan->issued_at->toDateString() : null,
            'type' => 'issued',
            'amount' => $loan->total_due,
            'balance' => $balance,
            'note' => 'Loan issued (principal+interest)'
        ];

        foreach ($loan->payments()->orderBy('paid_at')->get() as $p) {
            $balance = round($balance - $p->amount, 2);
            $ledger[] = [
                'date' => $p->paid_at->toDateString(),
                'type' => 'payment',
                'amount' => $p->amount,
                'balance' => $balance,
                'note' => $p->method ?? ''
            ];
        }

        return view('loans.show', compact('loan','ledger'));
    }

    // New method: get overdue loans / aging report
    public function overdue()
    {
        // Eloquent query for all overdue loans
        $overdue = Loan::where('status', '!=', 'paid')
            ->whereRaw('DATEDIFF(CURDATE(), first_due_date) > 0')
            ->orderByRaw('DATEDIFF(CURDATE(), first_due_date) DESC')
            ->get();

        // Optional: you can group into aging buckets
        $aging = [
            '1_30' => 0,
            '31_60' => 0,
            '61_90' => 0,
            'gt_90' => 0,
            'total' => 0
        ];

        foreach ($overdue as $loan) {
            $days = now()->diffInDays($loan->first_due_date, false);
            if ($days >= 1 && $days <= 30) $aging['1_30'] += $loan->balance;
            elseif ($days >= 31 && $days <= 60) $aging['31_60'] += $loan->balance;
            elseif ($days >= 61 && $days <= 90) $aging['61_90'] += $loan->balance;
            elseif ($days > 90) $aging['gt_90'] += $loan->balance;

            $aging['total'] += $loan->balance;
        }

        return view('loans.overdue', compact('overdue','aging'));
    }
}
