<?php

namespace App\Modules\Booking\Policies;

use App\Modules\Booking\Models\BookingSlotTemplate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingSlotTemplatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view booking slot templates');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BookingSlotTemplate $bookingSlotTemplate): bool
    {
        return $user->can('view booking slot templates');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create booking slot templates');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BookingSlotTemplate $bookingSlotTemplate): bool
    {
        return $user->can('edit booking slot templates');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BookingSlotTemplate $bookingSlotTemplate): bool
    {
        return $user->can('delete booking slot templates');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BookingSlotTemplate $bookingSlotTemplate): bool
    {
        return $user->can('restore booking slot templates');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BookingSlotTemplate $bookingSlotTemplate): bool
    {
        return $user->can('force delete booking slot templates');
    }
}
