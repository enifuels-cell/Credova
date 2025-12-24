<?php
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Collection<int, Property> $properties
 * @property-read Collection<int, Booking> $bookings
 * @property-read Collection<int, Review> $reviewsAsGuest
 * @property-read Collection<int, Review> $reviewsAsHost
 * @property-read Collection<int, SavedSearch> $savedSearches
 * @property-read Collection<int, Transaction> $transactions
 * @property-read IdentityVerification|null $identityVerification
 * @method bool hasRole(string|array|Role $roles, string $guard = null)
 * @method bool hasAnyRole(string|array|Role $roles, string $guard = null)
 * @method bool hasAllRoles(string|array|Role $roles, string $guard = null)
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany roles()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
        protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'bio',
        'profile_image',
        'avatar_url',
        'preferred_language',
        'preferred_currency',
        'timezone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'government_id_type',
        'government_id_number',
        'host_verification_documents',
        'recommendation_preferences',
        'mfa_enabled',
        'mfa_secret',
        'backup_codes',
        'last_activity_at',
        'risk_score',
        'device_fingerprints',
        'security_preferences',
        'social_tokens',
        'provider',
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'mfa_secret', // Keep MFA secret hidden
        'backup_codes', // Keep backup codes hidden
        'social_tokens', // Keep social tokens hidden
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'government_id_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'host_verified_at' => 'datetime',
            'host_verification_documents' => 'array',
            'recommendation_preferences' => 'array',
            'backup_codes' => 'array',
            'last_activity_at' => 'datetime',
            'risk_score' => 'decimal:2',
            'device_fingerprints' => 'array',
            'security_preferences' => 'array',
            'social_tokens' => 'array',
            'mfa_enabled' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is landlord
     */
    public function isLandlord(): bool
    {
        return $this->role === 'landlord';
    }

    /**
     * Check if user is tenant
     */
    public function isTenant(): bool
    {
        return $this->role === 'tenant';
    }



    // Relationships
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviewsAsGuest(): HasMany
    {
        return $this->hasMany(Review::class, 'guest_id');
    }

    public function reviewsAsHost(): HasMany
    {
        return $this->hasMany(Review::class, 'host_id');
    }

    public function savedSearches(): HasMany
    {
        return $this->hasMany(SavedSearch::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function identityVerification(): HasOne
    {
        return $this->hasOne(IdentityVerification::class);
    }

    // --- Custom Methods ---

    /**
     * Check if the user's government ID is verified.
     */
    public function isGovernmentIdVerified(): bool
    {
        return $this->government_id_verified_at !== null;
    }

    /**
     * Check if the user is a verified host.
     */
    public function isHostVerified(): bool
    {
        return $this->host_verified_at !== null;
    }


    // --- Accessors for clean data retrieval ---


    
    /**
     * Get the verification status as a string.
     */
    public function getVerificationStatusAttribute(): string
    {
        if ($this->isHostVerified()) {
            return 'host-verified';
        }
        
        if ($this->isGovernmentIdVerified()) {
            return 'id-verified';
        }
        
        if ($this->hasPendingVerification()) {
            return 'pending';
        }
        
        return 'not-verified';
    }

    // --- Accessors for clean data retrieval ---

    /**
     * Check if user has completed identity verification.
     */
    public function getIsIdentityVerifiedAttribute(): bool
    {
        return $this->identityVerification && $this->identityVerification->status === 'verified';
    }

    /**
     * Check if user has a pending identity verification.
     */
    public function getHasPendingVerificationAttribute(): bool
    {
        return $this->identityVerification && $this->identityVerification->status === 'pending';
    }
}
