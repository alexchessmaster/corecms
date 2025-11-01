<?php

namespace App\Policies;

use App\Models\User;
use App\Models\category;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view categories');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, category $category): bool
    {
        return $user->can('view categories') || ($category->user_id === $user->id && $user->can('view own categories'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create categories');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, category $category): bool
    {
        return $user->can('edit categories') || ($category->user_id === $user->id && $user->can('edit own categories'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, category $category): bool
    {
        return $user->can('delete categories') || ($category->user_id === $user->id && $user->can('delete own categories'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, category $category): bool
    {
        return $user->can('restore categories');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, category $category): bool
    {
        return $user->can('force delete categories');
    }
}
