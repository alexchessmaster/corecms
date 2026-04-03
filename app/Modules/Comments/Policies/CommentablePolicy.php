<?php

namespace App\Modules\Comments\Policies;

use App\Modules\Comments\Models\Commentable;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentablePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view commentables');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Commentable $commentable): bool
    {
        return $user->can('view commentables')
            || ($commentable->user_id === $user->id && $user->can('view own commentables'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create commentables');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Commentable $commentable): bool
    {
        return $user->can('edit commentables')
            || ($commentable->user_id === $user->id && $user->can('edit own commentables'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Commentable $commentable): bool
    {
        return $user->can('delete commentables')
            || ($commentable->user_id === $user->id && $user->can('delete own commentables'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Commentable $commentable): bool
    {
        return $user->can('restore commentables');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Commentable $commentable): bool
    {
        return $user->can('force delete commentables');
    }
}
