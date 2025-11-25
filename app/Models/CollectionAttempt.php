<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id','borrower_id','attempted_at','outcome','collected_amount','notes'
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
    ];

    public function loan() { return $this->belongsTo(Loan::class); }
    public function borrower() { return $this->belongsTo(Borrower::class); }
}
