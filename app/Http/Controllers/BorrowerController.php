<?php
namespace App\Http\Controllers;
use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    public function show(Borrower $borrower)
    {
        $borrower->load(['loans.payments','payments']);
        return view('borrowers.show', compact('borrower'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'first_name'=>'required',
            'last_name'=>'nullable',
            'phone'=>'nullable',
            'email'=>'nullable|email',
            'address'=>'nullable',
        ]);
        $b = Borrower::create($data);
        return redirect()->route('borrowers.show', $b->id)->with('success', 'Borrower created successfully!');
    }

    public function destroy(Borrower $borrower)
    {
        $borrower->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Borrower deleted successfully']);
        }

        return redirect()->back()->with('success', 'Borrower deleted successfully!');
    }
}
