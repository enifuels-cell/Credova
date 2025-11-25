<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_id','principal','interest_rate','total_due','balance',
        'issued_at','first_due_date','frequency','term','status','notes'
    ];

    protected $casts = [
        'issued_at' => 'date',
        'first_due_date' => 'date',
    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function collectionAttempts()
    {
        return $this->hasMany(CollectionAttempt::class);
    }

    // simple days past due from first_due_date
    public function daysPastDue()
    {
        if (!$this->first_due_date) return 0;
        return now()->diffInDays($this->first_due_date, false) * -1; // negative -> not due; but we'll compute more reliably in queries
    }
}
