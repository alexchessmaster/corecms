<?php

namespace App\Modules\Booking\Policies;

use App\Modules\Booking\Models\BookingReservation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingReservationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view booking reservations');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BookingReservation $bookingReservation): bool
    {
        return $user->can('view booking reservations')
            || ($bookingReservation->user_id === $user->id && $user->can('view own booking reservations'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create booking reservations');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BookingReservation $bookingReservation): bool
    {
        return $user->can('edit booking reservations')
            || ($bookingReservation->user_id === $user->id && $user->can('edit own booking reservations'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BookingReservation $bookingReservation): bool
    {
        return $user->can('delete booking reservations')
            || ($bookingReservation->user_id === $user->id && $user->can('delete own booking reservations'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BookingReservation $bookingReservation): bool
    {
        return $user->can('restore booking reservations');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BookingReservation $bookingReservation): bool
    {
        return $user->can('force delete booking reservations');
    }
}
