<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\IdentityVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IdentityVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the verification form
     */
    public function create()
    {
        $user = Auth::user();
        $verification = $user->identityVerification;
        
        return view('identity.verify', compact('verification'));
    }

    /**
     * Store a new verification request
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:passport,driver_license,national_id,other',
            'document_number' => 'required|string|max:50',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:18 years ago',
            'address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:20',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:100',
            'document_front' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'document_back' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'selfie_with_document' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'additional_documents.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'terms_accepted' => 'required|accepted',
            'data_processing_consent' => 'required|accepted',
        ]);

        $user = Auth::user();
        
        // Check if user already has a verification request
        $existingVerification = $user->identityVerification;
        if ($existingVerification && $existingVerification->status === 'pending') {
            return back()->with('error', 'You already have a pending verification request.');
        }

        // Upload documents
        $documentPaths = [];
        
        // Document front (required)
        $documentPaths['document_front'] = $request->file('document_front')
            ->store('identity-documents/' . $user->id, 'private');
        
        // Document back (optional)
        if ($request->hasFile('document_back')) {
            $documentPaths['document_back'] = $request->file('document_back')
                ->store('identity-documents/' . $user->id, 'private');
        }
        
        // Selfie with document (required)
        $documentPaths['selfie_with_document'] = $request->file('selfie_with_document')
            ->store('identity-documents/' . $user->id, 'private');
        
        // Additional documents (optional)
        $additionalDocs = [];
        if ($request->hasFile('additional_documents')) {
            foreach ($request->file('additional_documents') as $file) {
                $additionalDocs[] = $file->store('identity-documents/' . $user->id, 'private');
            }
        }

        // Create or update verification record
        $verification = IdentityVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'verification_id' => 'VER-' . strtoupper(Str::random(10)),
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relationship' => $request->emergency_contact_relationship,
                'document_front' => $documentPaths['document_front'],
                'document_back' => $documentPaths['document_back'] ?? null,
                'selfie_with_document' => $documentPaths['selfie_with_document'],
                'additional_documents' => !empty($additionalDocs) ? json_encode($additionalDocs) : null,
                'status' => 'pending',
                'submitted_at' => now(),
                'terms_accepted' => true,
                'data_processing_consent' => true,
                'notes' => $request->notes,
            ]
        );

        // Update user verification status
        $user->update(['is_identity_verified' => false]);

        // Notify admins about new verification request
        $this->notifyAdminsOfNewVerification($verification);

        return redirect()->route('identity.status')
            ->with('success', 'Identity verification submitted successfully. We\'ll review your documents within 2-3 business days.');
    }

    /**
     * Show verification status
     */
    public function status()
    {
        $user = Auth::user();
        $verification = $user->identityVerification;
        
        return view('identity.status', compact('verification'));
    }

    /**
     * Admin: List all verification requests
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('viewAny', IdentityVerification::class);
        
        $query = IdentityVerification::with('user')
            ->when($request->status, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->search, function ($q, $search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('verification_id', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
            })
            ->orderBy('submitted_at', 'desc');

        $verifications = $query->paginate(20);
        
        $stats = [
            'pending' => IdentityVerification::where('status', 'pending')->count(),
            'approved' => IdentityVerification::where('status', 'approved')->count(),
            'rejected' => IdentityVerification::where('status', 'rejected')->count(),
            'total' => IdentityVerification::count(),
        ];

        return view('admin.identity-verifications.index', compact('verifications', 'stats'));
    }

    /**
     * Admin: Show verification details
     */
    public function adminShow(IdentityVerification $verification)
    {
        $this->authorize('view', $verification);
        
        return view('admin.identity-verifications.show', compact('verification'));
    }

    /**
     * Admin: Approve verification
     */
    public function approve(Request $request, IdentityVerification $verification)
    {
        $this->authorize('update', $verification);
        
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $verification->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Update user verification status
        $verification->user->update(['is_identity_verified' => true]);

        // Send approval notification
        $this->sendVerificationNotification($verification, 'approved');

        return redirect()->route('admin.identity-verifications.index')
            ->with('success', 'Identity verification approved successfully.');
    }

    /**
     * Admin: Reject verification
     */
    public function reject(Request $request, IdentityVerification $verification)
    {
        $this->authorize('update', $verification);
        
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
            'rejection_reason' => 'required|string|in:document_quality,document_mismatch,information_mismatch,expired_document,fraudulent,other',
        ]);

        $verification->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes,
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Update user verification status
        $verification->user->update(['is_identity_verified' => false]);

        // Send rejection notification
        $this->sendVerificationNotification($verification, 'rejected');

        return redirect()->route('admin.identity-verifications.index')
            ->with('success', 'Identity verification rejected.');
    }

    /**
     * Download document file
     */
    public function downloadDocument(IdentityVerification $verification, $documentType)
    {
        $this->authorize('view', $verification);
        
        $documentPath = null;
        switch ($documentType) {
            case 'front':
                $documentPath = $verification->document_front;
                break;
            case 'back':
                $documentPath = $verification->document_back;
                break;
            case 'selfie':
                $documentPath = $verification->selfie_with_document;
                break;
        }

        if (!$documentPath || !Storage::disk('private')->exists($documentPath)) {
            abort(404, 'Document not found');
        }

        return Storage::disk('private')->download($documentPath);
    }

    /**
     * Resubmit verification after rejection
     */
    public function resubmit(IdentityVerification $verification)
    {
        $this->authorize('update', $verification);
        
        if ($verification->status !== 'rejected') {
            return back()->with('error', 'Only rejected verifications can be resubmitted.');
        }

        return view('identity.resubmit', compact('verification'));
    }

    /**
     * Update verification after resubmission
     */
    public function updateResubmission(Request $request, IdentityVerification $verification)
    {
        $this->authorize('update', $verification);
        
        if ($verification->status !== 'rejected') {
            return back()->with('error', 'Only rejected verifications can be resubmitted.');
        }

        $request->validate([
            'document_front' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'document_back' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'selfie_with_document' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'additional_documents.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'status' => 'pending',
            'submitted_at' => now(),
            'reviewed_by' => null,
            'reviewed_at' => null,
            'admin_notes' => null,
            'rejection_reason' => null,
        ];

        // Update documents if new ones are uploaded
        if ($request->hasFile('document_front')) {
            $updateData['document_front'] = $request->file('document_front')
                ->store('identity-documents/' . $verification->user_id, 'private');
        }

        if ($request->hasFile('document_back')) {
            $updateData['document_back'] = $request->file('document_back')
                ->store('identity-documents/' . $verification->user_id, 'private');
        }

        if ($request->hasFile('selfie_with_document')) {
            $updateData['selfie_with_document'] = $request->file('selfie_with_document')
                ->store('identity-documents/' . $verification->user_id, 'private');
        }

        if ($request->hasFile('additional_documents')) {
            $additionalDocs = [];
            foreach ($request->file('additional_documents') as $file) {
                $additionalDocs[] = $file->store('identity-documents/' . $verification->user_id, 'private');
            }
            $updateData['additional_documents'] = json_encode($additionalDocs);
        }

        if ($request->notes) {
            $updateData['notes'] = $request->notes;
        }

        $verification->update($updateData);

        // Notify admins
        $this->notifyAdminsOfNewVerification($verification);

        return redirect()->route('identity.status')
            ->with('success', 'Verification resubmitted successfully.');
    }

    /**
     * Send notification to admins about new verification
     */
    private function notifyAdminsOfNewVerification($verification)
    {
        // In a real application, you would send email notifications here
        // For now, we'll just log it or use a simple notification system
    }

    /**
     * Send verification status notification to user
     */
    private function sendVerificationNotification($verification, $status)
    {
        // In a real application, you would send email notifications here
        // For now, we'll just log it or use a simple notification system
    }
}
