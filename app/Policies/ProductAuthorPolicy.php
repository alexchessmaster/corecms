<?php

namespace App\Policies;

use App\Models\ProductAuthor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductAuthorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view product authors');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductAuthor $productAuthor): bool
    {
        return $user->can('view product authors')
            || ($productAuthor->user_id === $user->id && $user->can('view own product authors'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create product authors');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductAuthor $productAuthor): bool
    {
        return $user->can('edit product authors')
            || ($productAuthor->user_id === $user->id && $user->can('edit own product authors'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductAuthor $productAuthor): bool
    {
        return $user->can('delete product authors')
            || ($productAuthor->user_id === $user->id && $user->can('delete own product authors'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductAuthor $productAuthor): bool
    {
        return $user->can('restore product authors');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductAuthor $productAuthor): bool
    {
        return $user->can('force delete product authors');
    }
}
