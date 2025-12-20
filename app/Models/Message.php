<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'booking_id',
        'sender_id',
        'recipient_id',
        'sender_type',
        'message',
        'attachments',
        'is_automated',
        'message_type',
        'read_at',
        'is_important',
        'metadata',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_automated' => 'boolean',
        'read_at' => 'datetime',
        'is_important' => 'boolean',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the booking that owns the message
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the sender user
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient user
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    /**
     * Check if message is read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if message is unread
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Get formatted message time
     */
    public function getFormattedTimeAttribute(): string
    {
        if ($this->created_at->isToday()) {
            return $this->created_at->format('g:i A');
        } elseif ($this->created_at->isYesterday()) {
            return 'Yesterday ' . $this->created_at->format('g:i A');
        } elseif ($this->created_at->isCurrentWeek()) {
            return $this->created_at->format('l g:i A');
        } else {
            return $this->created_at->format('M j, Y g:i A');
        }
    }

    /**
     * Get message type badge color
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->message_type) {
            'check_in' => 'green',
            'check_out' => 'blue',
            'emergency' => 'red',
            'review_request' => 'purple',
            'cancellation' => 'orange',
            default => 'gray',
        };
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for important messages
     */
    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    /**
     * Scope for automated messages
     */
    public function scopeAutomated($query)
    {
        return $query->where('is_automated', true);
    }

    /**
     * Scope for specific message type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('message_type', $type);
    }
}
