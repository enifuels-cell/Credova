<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    use HasFactory;

    protected $fillable = ['first_name','last_name','phone','email','address'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Loan::class);
    }

    public function fullName()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
