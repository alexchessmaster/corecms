<?php

namespace App\Policies;

use App\Models\Field;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FieldPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view fields');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Field $field): bool
    {
        return $user->can('view fields') || ($field->user_id === $user->id && $user->can('view own fields'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create fields');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Field $field): bool
    {
        return $user->can('edit fields') || ($field->user_id === $user->id && $user->can('edit own fields'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Field $field): bool
    {
        return $user->can('delete fields') || ($field->user_id === $user->id && $user->can('delete own fields'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Field $field): bool
    {
        return $user->can('restore fields');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Field $field): bool
    {
        return $user->can('force delete fields');
    }
}
