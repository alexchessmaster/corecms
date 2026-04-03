<?php

namespace App\Modules\Widgets\Policies;

use App\Models\User;
use App\Modules\Widgets\Models\WidgetFieldValues;
use Illuminate\Auth\Access\Response;

class WidgetFieldValuesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view widget field values');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WidgetFieldValues $widgetFieldValues): bool
    {
        return $user->can('view widget field values')
            || ($widgetFieldValues->user_id === $user->id && $user->can('view own widget field values'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create widget field values');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WidgetFieldValues $widgetFieldValues): bool
    {
        return $user->can('edit widget field values')
            || ($widgetFieldValues->user_id === $user->id && $user->can('edit own widget field values'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WidgetFieldValues $widgetFieldValues): bool
    {
        return $user->can('delete widget field values')
            || ($widgetFieldValues->user_id === $user->id && $user->can('delete own widget field values'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WidgetFieldValues $widgetFieldValues): bool
    {
        return $user->can('restore widget field values');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WidgetFieldValues $widgetFieldValues): bool
    {
        return $user->can('force delete widget field values');
    }
}
