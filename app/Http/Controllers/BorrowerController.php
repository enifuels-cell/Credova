<?php
namespace App\Http\Controllers;
use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    public function index()
    {
        $borrowers = Borrower::with('loans')->paginate(20);
        return view('borrowers.index', compact('borrowers'));
    }

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
}
