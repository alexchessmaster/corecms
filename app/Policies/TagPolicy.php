<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Articles\Models\tag;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view tags') || $user->can('view own tags');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, tag $tag): bool
    {
        return $user->can('view tags')
            || ($tag->user_id === $user->id && $user->can('view own tags'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create tags');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, tag $tag): bool
    {
        return $user->can('edit tags')
            || ($tag->user_id === $user->id && $user->can('edit own tags'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, tag $tag): bool
    {
        return $user->can('delete tags')
            || ($tag->user_id === $user->id && $user->can('delete own tags'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, tag $tag): bool
    {
        return $user->can('restore tags');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, tag $tag): bool
    {
        return $user->can('force delete tags');
    }
}
