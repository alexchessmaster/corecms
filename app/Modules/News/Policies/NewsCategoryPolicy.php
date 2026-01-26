<?php

namespace App\Modules\News\Policies;

use App\Modules\News\Models\NewsCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NewsCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view news categories') || $user->can('view own news categories');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NewsCategory $newsCategory): bool
    {
        return $user->can('view news categories')
            || ($newsCategory->user_id === $user->id && $user->can('view own news categories'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create news categories');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NewsCategory $newsCategory): bool
    {
        return $user->can('edit news categories')
            || ($newsCategory->user_id === $user->id && $user->can('edit own news categories'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NewsCategory $newsCategory): bool
    {
        return $user->can('delete news categories')
            || ($newsCategory->user_id === $user->id && $user->can('delete own news categories'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, NewsCategory $newsCategory): bool
    {
        return $user->can('restore news categories');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, NewsCategory $newsCategory): bool
    {
        return $user->can('force delete news categories');
    }
}
