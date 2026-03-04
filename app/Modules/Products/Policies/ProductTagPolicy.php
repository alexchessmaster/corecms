<?php

namespace App\Modules\Products\Policies;

use App\Modules\Products\Models\ProductTag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductTagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view product tags') || $user->can('view own product tags');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductTag $productTag): bool
    {
        return $user->can('view product tags') || ($productTag->user_id === $user->id && $user->can('view own product tags'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create product tags');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductTag $productTag): bool
    {
        return $user->can('edit product tags') || ($productTag->user_id === $user->id && $user->can('edit own product tags'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductTag $productTag): bool
    {
        return $user->can('delete product tags') || ($productTag->user_id === $user->id && $user->can('delete own product tags'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductTag $productTag): bool
    {
        return $user->can('restore product tags');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductTag $productTag): bool
    {
        return $user->can('force delete product tags');
    }
}
