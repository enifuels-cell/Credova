<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedSearch extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'search_criteria',
        'email_alerts',
        'alert_frequency',
        'last_alert_sent',
        'is_active',
    ];

    protected $casts = [
        'search_criteria' => 'array',
        'email_alerts' => 'boolean',
        'last_alert_sent' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the saved search
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Execute the saved search and return matching properties
     */
    public function executeSearch()
    {
        $query = Property::query();
        $criteria = $this->search_criteria ?? [];

        // Apply location filter
        if (!empty($criteria['location'])) {
            $query->where(function($q) use ($criteria) {
                $q->where('city', 'like', '%' . $criteria['location'] . '%')
                  ->orWhere('state', 'like', '%' . $criteria['location'] . '%')
                  ->orWhere('address', 'like', '%' . $criteria['location'] . '%');
            });
        }

        // Apply price range filter
        if (!empty($criteria['min_price'])) {
            $query->where('price_per_night', '>=', $criteria['min_price']);
        }
        if (!empty($criteria['max_price'])) {
            $query->where('price_per_night', '<=', $criteria['max_price']);
        }

        // Apply guest count filter
        if (!empty($criteria['guests'])) {
            $query->where('max_guests', '>=', $criteria['guests']);
        }

        // Apply bedroom filter
        if (!empty($criteria['bedrooms'])) {
            $query->where('bedrooms', '>=', $criteria['bedrooms']);
        }

        // Apply bathroom filter
        if (!empty($criteria['bathrooms'])) {
            $query->where('bathrooms', '>=', $criteria['bathrooms']);
        }

        // Apply property type filter
        if (!empty($criteria['property_type'])) {
            $query->where('property_type', $criteria['property_type']);
        }

        // Apply amenities filter
        if (!empty($criteria['amenities']) && is_array($criteria['amenities'])) {
            foreach ($criteria['amenities'] as $amenity) {
                $query->whereJsonContains('amenities', $amenity);
            }
        }

        // Apply availability filter
        if (!empty($criteria['check_in']) && !empty($criteria['check_out'])) {
            $query->whereDoesntHave('bookings', function($q) use ($criteria) {
                $q->where('status', '!=', 'cancelled')
                  ->where(function($dateQuery) use ($criteria) {
                      $dateQuery->whereBetween('check_in_date', [$criteria['check_in'], $criteria['check_out']])
                               ->orWhereBetween('check_out_date', [$criteria['check_in'], $criteria['check_out']])
                               ->orWhere(function($overlapQuery) use ($criteria) {
                                   $overlapQuery->where('check_in_date', '<=', $criteria['check_in'])
                                               ->where('check_out_date', '>=', $criteria['check_out']);
                               });
                  });
            });
        }

        return $query->where('is_active', true)->get();
    }

    /**
     * Check if alerts should be sent based on frequency
     */
    public function shouldSendAlert(): bool
    {
        if (!$this->email_alerts || !$this->is_active) {
            return false;
        }

        if (!$this->last_alert_sent) {
            return true;
        }

        $hours = match($this->alert_frequency) {
            'daily' => 24,
            'weekly' => 168,
            'monthly' => 720,
            default => 24,
        };

        return $this->last_alert_sent->addHours($hours)->isPast();
    }

    /**
     * Mark alert as sent
     */
    public function markAlertSent(): bool
    {
        return $this->update(['last_alert_sent' => now()]);
    }

    /**
     * Get search criteria summary for display
     */
    public function getSearchSummaryAttribute(): string
    {
        $criteria = $this->search_criteria ?? [];
        $parts = [];

        if (!empty($criteria['location'])) {
            $parts[] = $criteria['location'];
        }

        if (!empty($criteria['guests'])) {
            $parts[] = $criteria['guests'] . ' guest' . ($criteria['guests'] > 1 ? 's' : '');
        }

        if (!empty($criteria['min_price']) || !empty($criteria['max_price'])) {
            $priceRange = '$';
            $priceRange .= $criteria['min_price'] ?? '0';
            $priceRange .= ' - $';
            $priceRange .= $criteria['max_price'] ?? '∞';
            $parts[] = $priceRange;
        }

        return implode(' • ', $parts) ?: 'All properties';
    }

    /**
     * Scope for active searches
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for searches with email alerts enabled
     */
    public function scopeWithAlerts($query)
    {
        return $query->where('email_alerts', true);
    }

    /**
     * Scope for searches that need alerts
     */
    public function scopeNeedsAlert($query)
    {
        return $query->where('email_alerts', true)
                    ->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('last_alert_sent')
                          ->orWhere('last_alert_sent', '<=', now()->subDay());
                    });
    }
}
