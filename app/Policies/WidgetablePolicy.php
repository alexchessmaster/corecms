<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Widgetable;
use Illuminate\Auth\Access\Response;

class WidgetablePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view widgetables');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Widgetable $widgetable): bool
    {
        return $user->can('view widgetables') 
            || ($widgetable->user_id === $user->id && $user->can('view own widgetables'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create widgetables');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Widgetable $widgetable): bool
    {
        return $user->can('edit widgetables') 
            || ($widgetable->user_id === $user->id && $user->can('edit own widgetables'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Widgetable $widgetable): bool
    {
        return $user->can('delete widgetables') 
            || ($widgetable->user_id === $user->id && $user->can('delete own widgetables'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Widgetable $widgetable): bool
    {
        return $user->can('restore widgetables');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Widgetable $widgetable): bool
    {
        return $user->can('force delete widgetables');
    }
}
