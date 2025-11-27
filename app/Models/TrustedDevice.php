<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrustedDevice extends Model
{
    use HasFactory;

    protected $table = 'trusted_devices';

    protected $fillable = [
        'user_id',
        'device_fingerprint',
        'device_name',
        'device_type',
        'browser',
        'os',
        'ip_address',
        'is_trusted',
        'last_used_at',
        'trusted_at',
    ];

    protected $casts = [
        'is_trusted' => 'boolean',
        'last_used_at' => 'datetime',
        'trusted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
