<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentityVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'verification_id',
        'document_type',
        'document_number',
        'full_name',
        'date_of_birth',
        'address',
        'phone_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'document_front',
        'document_back',
        'selfie_with_document',
        'additional_documents',
        'status',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'admin_notes',
        'rejection_reason',
        'terms_accepted',
        'data_processing_consent',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'terms_accepted' => 'boolean',
        'data_processing_consent' => 'boolean',
        'additional_documents' => 'array',
    ];

    /**
     * Get the user that owns the verification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who reviewed the verification
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Get formatted document type
     */
    public function getFormattedDocumentTypeAttribute(): string
    {
        return match($this->document_type) {
            'passport' => 'Passport',
            'driver_license' => 'Driver\'s License',
            'national_id' => 'National ID',
            'other' => 'Other Document',
            default => ucfirst($this->document_type),
        };
    }

    /**
     * Get formatted rejection reason
     */
    public function getFormattedRejectionReasonAttribute(): string
    {
        if (!$this->rejection_reason) {
            return '';
        }

        return match($this->rejection_reason) {
            'document_quality' => 'Poor Document Quality',
            'document_mismatch' => 'Document Information Mismatch',
            'information_mismatch' => 'Personal Information Mismatch',
            'expired_document' => 'Expired Document',
            'fraudulent' => 'Fraudulent Document',
            'other' => 'Other Reason',
            default => ucfirst(str_replace('_', ' ', $this->rejection_reason)),
        };
    }

    /**
     * Check if verification is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if verification is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if verification is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get additional documents as array
     */
    public function getAdditionalDocumentsArrayAttribute(): array
    {
        if (!$this->additional_documents) {
            return [];
        }

        return is_string($this->additional_documents) 
            ? json_decode($this->additional_documents, true) 
            : $this->additional_documents;
    }

    /**
     * Get days since submission
     */
    public function getDaysSinceSubmissionAttribute(): int
    {
        return $this->submitted_at ? $this->submitted_at->diffInDays(now()) : 0;
    }

    /**
     * Check if documents are complete
     */
    public function hasCompleteDocuments(): bool
    {
        return !empty($this->document_front) && !empty($this->selfie_with_document);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Pending verifications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Approved verifications
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Rejected verifications
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Recent submissions
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('submitted_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Overdue for review
     */
    public function scopeOverdue($query, $days = 3)
    {
        return $query->where('status', 'pending')
                    ->where('submitted_at', '<=', now()->subDays($days));
    }
}
