<?php

namespace App\Modules\Products\Policies;

use App\Modules\Products\Models\ProductCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view product categories') || $user->can('view own product categories');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('view product categories')
            || ($productCategory->user_id === $user->id && $user->can('view own product categories'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create product categories');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('edit product categories')
            || ($productCategory->user_id === $user->id && $user->can('edit own product categories'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('delete product categories')
            || ($productCategory->user_id === $user->id && $user->can('delete own product categories'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('restore product categories');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductCategory $productCategory): bool
    {
        return $user->can('force delete product categories');
    }
}
