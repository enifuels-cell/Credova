<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class collector extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone','email','user_id'];

    public function attempts()
    {
        return $this->hasMany(CollectionAttempt::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
