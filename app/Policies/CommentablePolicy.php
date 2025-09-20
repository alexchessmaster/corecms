<?php

namespace App\Policies;

use App\Models\Commentable;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentablePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor', 'author']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Commentable $commentable): bool
    {
        return in_array($user->role, ['admin', 'editor']) || ($commentable->user_id === $user->id && $user->role === 'author');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor', 'author']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Commentable $commentable): bool
    {
        return in_array($user->role, ['admin', 'editor']) || ($commentable->user_id === $user->id && $user->role === 'author');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Commentable $commentable): bool
    {
        return in_array($user->role, ['admin', 'editor']) || ($commentable->user_id === $user->id && $user->role === 'author');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Commentable $commentable): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Commentable $commentable): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }
}
