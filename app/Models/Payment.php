<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['loan_id','amount','paid_at','method','notes'];

    protected $casts = [
        'paid_at' => 'date',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function borrower()
    {
        return $this->loan->borrower;
    }
}
