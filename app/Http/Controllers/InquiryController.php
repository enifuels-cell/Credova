<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Inquiry;
use App\Http\Requests\InquiryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    /**
     * Store a new inquiry
     */
    public function store(InquiryRequest $request, Property $property)
    {
        $data = $request->validated();
        $data['property_id'] = $property->id;

        // If user is logged in, associate the inquiry
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        Inquiry::create($data);

        return redirect()->back()->with('success', 'Your inquiry has been sent successfully! The landlord will contact you soon.');
    }

    /**
     * Display user's inquiries (for tenants)
     */
    public function myInquiries()
    {
        $inquiries = Inquiry::with(['property.images', 'property.barangay'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('inquiries.index', compact('inquiries'));
    }

    /**
     * Display inquiries for landlord's properties
     */
    public function landlordInquiries()
    {
        $inquiries = Inquiry::with(['property.images', 'user'])
            ->whereHas('property', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->paginate(10);

        return view('landlord.inquiries.index', compact('inquiries'));
    }

    /**
     * Update inquiry status (for landlords)
     */
    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        // Check if user owns the property
        if ($inquiry->property->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,read,responded,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $inquiry->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Inquiry status updated successfully.');
    }
}
