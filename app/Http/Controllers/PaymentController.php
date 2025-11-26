<?php
namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Loan;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $r)
    {
        $data = $r->validate([
            'loan_id'=>'required|exists:loans,id',
            'amount'=>'required|numeric|min:0.01',
            'paid_at'=>'required|date',
            'method'=>'nullable|string',
            'notes'=>'nullable|string',
        ]);

        $payment = Payment::create($data);

        // update loan balance
        $loan = Loan::find($data['loan_id']);
        $loan->balance = round($loan->balance - $data['amount'], 2);
        if ($loan->balance <= 0) {
            $loan->status = 'paid';
            $loan->balance = 0;
        }
        $loan->save();

        // Return JSON if API request, otherwise redirect
        if ($r->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'payment' => $payment,
                'loan' => $loan
            ]);
        }

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }
}
