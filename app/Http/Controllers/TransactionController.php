<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's transactions
     */
    public function index()
    {
        $transactions = Auth::user()->transactions()->latest()->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Store a new transaction request
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:deposit,withdrawal',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500'
        ]);

        Auth::user()->transactions()->create([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Transaction request submitted successfully!');
    }

    /**
     * Admin view for all transactions
     */
    public function adminIndex()
    {
        $transactions = Transaction::with('user')->latest()->paginate(15);
        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Approve a transaction
     */
    public function approve(Transaction $transaction)
    {
        $transaction->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Transaction approved successfully!');
    }

    /**
     * Reject a transaction
     */
    public function reject(Transaction $transaction)
    {
        $transaction->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Transaction rejected successfully!');
    }
}
