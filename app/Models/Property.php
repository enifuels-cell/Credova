<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'property_type_id',
        'barangay_id',
        'title',
        'slug',
        'description',
        'address',
        'landmark',
        'price',
        'deposit',
        'advance',
        'bedrooms',
        'bathrooms',
        'floor_area',
        'lot_area',
        'floor_level',
        'max_occupants',
        'is_furnished',
        'pets_allowed',
        'parking_available',
        'parking_slots',
        'minimum_lease_months',
        'available_from',
        'status',
        'is_featured',
        'views_count',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'deposit' => 'decimal:2',
        'advance' => 'decimal:2',
        'floor_area' => 'decimal:2',
        'lot_area' => 'decimal:2',
        'is_furnished' => 'boolean',
        'pets_allowed' => 'boolean',
        'parking_available' => 'boolean',
        'is_featured' => 'boolean',
        'available_from' => 'date:Y-m-d',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->title) . '-' . Str::random(8);
            }
        });
    }

    // ========================
    // Relationships
    // ========================

    /**
     * The user (landlord) who owns this property.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for user relationship (more semantic for landlord context).
     */
    public function landlord(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The type of property (e.g., Apartment, House).
     */
    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    /**
     * The barangay where the property is located.
     */
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Property images, ordered by sort order.
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    /**
     * The primary image of the property.
     * Amenities associated with the property.
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity')->withTimestamps();
    }

    /**
     * Inquiries made about this property.
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Bookings for this property.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Reviews for this property.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Favorites (users who saved this property).
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    // ========================
    // Accessors & Mutators
    // ========================

    /**
     * Get the average rating from approved reviews.
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->where('is_approved', true)->avg('rating') ?? 0, 1);
    }

    /**
     * Get the count of approved reviews.
     */
    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    /**
     * Get the formatted price in Philippine Peso.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₱' . number_format($this->price, 2);
    }

    // ========================
    // Scopes
    // ========================

    /**
     * Scope: Only active properties.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Only featured properties.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: Properties that are currently available (active status).
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    // ========================
    // Helper Methods
    // ========================

    /**
     * Increment the view count for this property.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Check if the property is available between given dates.
     *
     * @param  string  $startDate  Y-m-d format
     * @param  string  $endDate    Y-m-d format
     */
    public function isAvailable(string $startDate, string $endDate): bool
    {
        return ! $this->bookings()
            ->where('status', 'confirmed')
            ->where(function (Builder $query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function (Builder $query) use ($startDate, $endDate) {
                          $query->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();
    }
}
