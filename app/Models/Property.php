<?php

namespace App\Models;

<<<<<<< HEAD
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

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'deposit' => 'decimal:2',
            'advance' => 'decimal:2',
            'floor_area' => 'decimal:2',
            'lot_area' => 'decimal:2',
            'is_furnished' => 'boolean',
            'pets_allowed' => 'boolean',
            'parking_available' => 'boolean',
            'is_featured' => 'boolean',
            'available_from' => 'date',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->title) . '-' . uniqid();
            }
        });
    }

    /**
     * Get the landlord (owner) of the property
=======
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Property extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'price_per_night',
        'image',
        'bedrooms',
        'bathrooms',
        'max_guests',
        'property_type',
        'amenities',
        'house_rules',
        'check_in_time',
        'check_out_time',
        'cancellation_policy',
        'instant_book',
        'featured',
        'is_active',
        'lat',
        'lng'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'amenities' => 'array',
        'instant_book' => 'boolean',
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
    ];

    /**
     * Get the user that owns the property
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
<<<<<<< HEAD
     * Alias for user relationship
     */
    public function landlord(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the property type
     */
    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    /**
     * Get the barangay
     */
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Get property images
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    /**
     * Get primary image
     */
    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }

    /**
     * Get amenities
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity')
            ->withTimestamps();
    }

    /**
     * Get inquiries
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Get bookings
=======
     * Get the bookings for the property
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
<<<<<<< HEAD
     * Get reviews
=======
     * Get the images for the property
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    /**
     * Get the reviews for the property
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
<<<<<<< HEAD
     * Get favorites
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    /**
     * Get review count
     */
    public function getReviewCountAttribute()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    /**
     * Format price in Philippine Peso
     */
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    /**
     * Scope for active properties
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for featured properties
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for available properties
     */
    public function scopeAvailable($query)
    {
        return $query->whereIn('status', ['active']);
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
=======
     * Get the primary image for the property
     */
    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }

    /**
     * Scope a query to only include active properties.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured properties.
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('featured', true);
    }

    /**
     * Scope a query to filter by property type.
     */
    public function scopeOfType(Builder $query, string $type): void
    {
        $query->where('property_type', $type);
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopePriceRange(Builder $query, float $min, float $max): void
    {
        $query->whereBetween('price_per_night', [$min, $max]);
    }

    /**
     * Scope a query to filter by location.
     */
    public function scopeLocation(Builder $query, string $location): void
    {
        $query->where('location', 'LIKE', "%{$location}%");
    }

    /**
     * Scope a query to filter by amenities.
     */
    public function scopeHasAmenities(Builder $query, array $amenities): void
    {
        foreach ($amenities as $amenity) {
            $query->whereJsonContains('amenities', $amenity);
        }
    }

    /**
     * Get available amenities list
     */
    public static function getAvailableAmenities(): array
    {
        return [
            'wifi' => 'Wi-Fi',
            'kitchen' => 'Kitchen',
            'washer' => 'Washer',
            'dryer' => 'Dryer',
            'air_conditioning' => 'Air Conditioning',
            'heating' => 'Heating',
            'pool' => 'Pool',
            'hot_tub' => 'Hot Tub',
            'parking' => 'Free Parking',
            'gym' => 'Gym',
            'tv' => 'TV',
            'workspace' => 'Dedicated Workspace',
            'smoking_allowed' => 'Smoking Allowed',
            'pets_allowed' => 'Pets Allowed',
            'events_allowed' => 'Events Allowed',
            'breakfast' => 'Breakfast',
            'laptop_friendly' => 'Laptop Friendly Workspace',
            'hair_dryer' => 'Hair Dryer',
            'iron' => 'Iron',
            'security_cameras' => 'Security Cameras',
            'first_aid_kit' => 'First Aid Kit',
            'fire_extinguisher' => 'Fire Extinguisher',
            'essentials' => 'Essentials (towels, bed sheets, soap, toilet paper)',
        ];
    }

    /**
     * Get property types list
     */
    public static function getPropertyTypes(): array
    {
        return [
            'apartment' => 'Apartment',
            'house' => 'House',
            'villa' => 'Villa',
            'condo' => 'Condo',
            'studio' => 'Studio',
            'loft' => 'Loft',
        ];
    }

    /**
     * Get cancellation policies list
     */
    public static function getCancellationPolicies(): array
    {
        return [
            'flexible' => 'Flexible - Free cancellation up to 24 hours before check-in',
            'moderate' => 'Moderate - Free cancellation up to 5 days before check-in',
            'strict' => 'Strict - Free cancellation up to 30 days before check-in',
        ];
    }

    /**
     * Check if property is available for given dates
     */
    public function isAvailable($startDate, $endDate): bool
    {
        return !$this->bookings()
            ->where('status', 'confirmed')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
    }
}
