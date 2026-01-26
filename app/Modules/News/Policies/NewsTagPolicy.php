<?php

namespace App\Modules\News\Policies;

use App\Modules\News\Models\NewsTag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NewsTagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view news tags') || $user->can('view own news tags');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NewsTag $newsTag): bool
    {
        return $user->can('view news tags') || ($newsTag->user_id === $user->id && $user->can('view own news tags'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create news tags');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NewsTag $newsTag): bool
    {
        return $user->can('edit news tags') || ($newsTag->user_id === $user->id && $user->can('edit own news tags'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NewsTag $newsTag): bool
    {
        return $user->can('delete news tags') || ($newsTag->user_id === $user->id && $user->can('delete own news tags'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, NewsTag $newsTag): bool
    {
        return $user->can('restore news tags');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, NewsTag $newsTag): bool
    {
        return $user->can('force delete news tags');
    }
}
