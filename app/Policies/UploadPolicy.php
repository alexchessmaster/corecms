<?php

namespace App\Policies;

use App\Models\Upload;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UploadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view uploads');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Upload $upload): bool
    {
        return $user->can('view uploads') || ($upload->user_id === $user->id && $user->can('view own uploads'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create uploads');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Upload $upload): bool
    {
        return $user->can('edit uploads') || ($upload->user_id === $user->id && $user->can('edit own uploads'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Upload $upload): bool
    {
        return $user->can('delete uploads') || ($upload->user_id === $user->id && $user->can('delete own uploads'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Upload $upload): bool
    {
        return $user->can('restore uploads');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Upload $upload): bool
    {
        return $user->can('force delete uploads');
    }
}
