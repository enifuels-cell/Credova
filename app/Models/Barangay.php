<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barangay extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'district',
        'latitude',
        'longitude',
        'area_type',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Check if barangay has coordinates
     */
    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Get formatted area type display name
     */
    public function getAreaTypeLabelAttribute(): string
    {
        return match($this->area_type) {
            'uptown' => 'Uptown',
            'downtown' => 'Downtown/Centro',
            'suburban' => 'Suburban',
            'rural' => 'Rural',
            default => 'General',
        };
    }

    /**
     * Scope for uptown areas
     */
    public function scopeUptown($query)
    {
        return $query->where('area_type', 'uptown');
    }

    /**
     * Scope for downtown areas
     */
    public function scopeDowntown($query)
    {
        return $query->where('area_type', 'downtown');
    }

    /**
     * Scope to include only barangays with coordinates
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /**
     * Get properties in this barangay
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Scope for active barangays
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
