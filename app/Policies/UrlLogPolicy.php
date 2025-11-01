<?php

namespace App\Policies;

use App\Models\UrlLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UrlLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view url logs');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UrlLog $urlLog): bool
    {
        return $user->can('view url logs')
            || ($urlLog->user_id === $user->id && $user->can('view own url logs'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create url logs');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UrlLog $urlLog): bool
    {
        return $user->can('edit url logs')
            || ($urlLog->user_id === $user->id && $user->can('edit own url logs'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UrlLog $urlLog): bool
    {
        return $user->can('delete url logs')
            || ($urlLog->user_id === $user->id && $user->can('delete own url logs'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UrlLog $urlLog): bool
    {
        return $user->can('restore url logs');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UrlLog $urlLog): bool
    {
        return $user->can('force delete url logs');
    }
}
