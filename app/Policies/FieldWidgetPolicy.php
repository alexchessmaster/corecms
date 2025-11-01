<?php

namespace App\Policies;

use App\Models\FieldWidget;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FieldWidgetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view field widgets');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FieldWidget $fieldWidget): bool
    {
        return $user->can('view field widgets')
            || ($fieldWidget->user_id === $user->id && $user->can('view own field widgets'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create field widgets');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FieldWidget $fieldWidget): bool
    {
        return $user->can('edit field widgets')
            || ($fieldWidget->user_id === $user->id && $user->can('edit own field widgets'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FieldWidget $fieldWidget): bool
    {
        return $user->can('delete field widgets')
            || ($fieldWidget->user_id === $user->id && $user->can('delete own field widgets'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FieldWidget $fieldWidget): bool
    {
        return $user->can('restore field widgets');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FieldWidget $fieldWidget): bool
    {
        return $user->can('force delete field widgets');
    }
}
