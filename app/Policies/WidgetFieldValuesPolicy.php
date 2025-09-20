<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WidgetFieldValues;
use Illuminate\Auth\Access\Response;

class WidgetFieldValuesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor', 'author']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WidgetFieldValues $widgetFieldValues): bool
    {
        return in_array($user->role, ['admin', 'editor']) || ($widgetFieldValues->user_id === $user->id && $user->role === 'author');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor', 'author']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WidgetFieldValues $widgetFieldValues): bool
    {
        return in_array($user->role, ['admin', 'editor']) || ($widgetFieldValues->user_id === $user->id && $user->role === 'author');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WidgetFieldValues $widgetFieldValues): bool
    {
        return in_array($user->role, ['admin', 'editor']) || ($widgetFieldValues->user_id === $user->id && $user->role === 'author');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WidgetFieldValues $widgetFieldValues): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WidgetFieldValues $widgetFieldValues): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }
}
