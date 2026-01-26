<?php

namespace App\Modules\News\Policies;

use App\Modules\News\Models\NewsAuthor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NewsAuthorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view news authors') || $user->can('view own news authors');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NewsAuthor $newsAuthor): bool
    {
        return $user->can('view news authors')
            || ($newsAuthor->user_id === $user->id && $user->can('view own news authors'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create news authors');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NewsAuthor $newsAuthor): bool
    {
        return $user->can('edit news authors')
            || ($newsAuthor->user_id === $user->id && $user->can('edit own news authors'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NewsAuthor $newsAuthor): bool
    {
        return $user->can('delete news authors')
            || ($newsAuthor->user_id === $user->id && $user->can('delete own news authors'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, NewsAuthor $newsAuthor): bool
    {
        return $user->can('restore news authors');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, NewsAuthor $newsAuthor): bool
    {
        return $user->can('force delete news authors');
    }
}
