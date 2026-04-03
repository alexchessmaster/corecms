<?php

namespace App\Modules\Users\Policies;

use App\Modules\Users\Models\Author;
use App\Models\User;

class AuthorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view authors') || $user->can('view own authors');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Author $author): bool
    {
        return $user->can('view authors')
            || ($author->user_id === $user->id && $user->can('view own authors'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create authors');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Author $author): bool
    {
        return $user->can('edit authors')
            || ($author->user_id === $user->id && $user->can('edit own authors'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Author $author): bool
    {
        return $user->can('delete authors')
            || ($author->user_id === $user->id && $user->can('delete own authors'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Author $author): bool
    {
        return $user->can('restore authors');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Author $author): bool
    {
        return $user->can('force delete authors');
    }
}
