<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Property $property): bool
    {
        return $property->is_active || $user->id === $property->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('landlord');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Property $property): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Property $property): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage property images.
     */
    public function manageImages(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can toggle property status.
     */
    public function toggleStatus(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can book the property.
     */
    public function book(User $user, Property $property): bool
    {
        return $user->hasRole('renter') && 
               $user->id !== $property->user_id && 
               $property->is_active;
    }
}
