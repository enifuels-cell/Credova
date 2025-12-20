<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
=======
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyImage extends Model
{
<<<<<<< HEAD
    use HasFactory;

    protected $fillable = [
        'property_id',
        'image_path',
        'caption',
        'sort_order',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    /**
     * Get the property
=======
    protected $fillable = [
        'property_id',
        'image_path',
        'alt_text',
        'sort_order',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the property that owns the image
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
<<<<<<< HEAD
     * Get full image URL
     */
    public function getImageUrlAttribute()
=======
     * Get the full URL for the image
     */
    public function getUrlAttribute(): string
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
    {
        return asset('storage/' . $this->image_path);
    }
}
