<?php

namespace App\Policies;

use App\Models\BookingTimeSlot;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingTimeSlotPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view booking time slots');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BookingTimeSlot $bookingTimeSlot): bool
    {
        return $user->can('view booking time slots');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create booking time slots');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BookingTimeSlot $bookingTimeSlot): bool
    {
        return $user->can('edit booking time slots');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BookingTimeSlot $bookingTimeSlot): bool
    {
        return $user->can('delete booking time slots');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BookingTimeSlot $bookingTimeSlot): bool
    {
        return $user->can('restore booking time slots');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BookingTimeSlot $bookingTimeSlot): bool
    {
        return $user->can('force delete booking time slots');
    }
}
