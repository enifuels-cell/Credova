<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
        'booking_id',
        'rating',
        'comment',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Get the property
=======
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    protected $fillable = [
        'property_id',
        'booking_id',
        'guest_id',
        'host_id',
        'rating',
        'cleanliness_rating',
        'communication_rating',
        'location_rating',
        'value_rating',
        'title',
        'comment',
        'photos',
        'host_response',
        'host_response_date',
        'is_verified',
        'is_featured',
    ];

    protected $casts = [
        'rating' => 'integer',
        'cleanliness_rating' => 'integer',
        'communication_rating' => 'integer',
        'location_rating' => 'integer',
        'value_rating' => 'integer',
        'photos' => 'array',
        'host_response_date' => 'datetime',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the property that owns the review
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
<<<<<<< HEAD
     * Get the reviewer
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booking
=======
     * Get the booking that owns the review
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
<<<<<<< HEAD
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
=======
     * Get the guest who wrote the review
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    /**
     * Get the host who received the review
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /**
     * Calculate average rating from all categories
     */
    public function getAverageRatingAttribute(): float
    {
        $ratings = [
            $this->cleanliness_rating,
            $this->communication_rating,
            $this->location_rating,
            $this->value_rating,
        ];

        $validRatings = array_filter($ratings, fn($rating) => !is_null($rating));
        
        return count($validRatings) > 0 ? round(array_sum($validRatings) / count($validRatings), 1) : 0;
    }

    /**
     * Check if review has host response
     */
    public function hasHostResponse(): bool
    {
        return !empty($this->host_response);
    }

    /**
     * Get formatted review date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('F Y');
    }

    /**
     * Scope for verified reviews
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for featured reviews
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for reviews with photos
     */
    public function scopeWithPhotos($query)
    {
        return $query->whereNotNull('photos')
                    ->where('photos', '!=', '[]');
    }

    /**
     * Scope for recent reviews
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
    }
}
