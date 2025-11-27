<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPin extends Model
{
    use HasFactory;

    protected $table = 'user_pins';

    protected $fillable = [
        'user_id',
        'pin_hash',
        'is_set',
        'pin_set_at',
    ];

    protected $casts = [
        'is_set' => 'boolean',
        'pin_set_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
