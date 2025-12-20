<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user who owns the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
