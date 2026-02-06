<?php

namespace App\Policies;

use App\Modules\AiChat\Models\AiPersona;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AiPersonaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view ai personas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AiPersona $aiPersona): bool
    {
        return $user->can('view ai personas')
            || ($user->id === $aiPersona->created_by_user_id && $user->can('view own ai personas'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ai personas');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AiPersona $aiPersona): bool
    {
        return $user->can('edit ai personas')
            || ($user->id === $aiPersona->created_by_user_id && $user->can('edit own ai personas'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AiPersona $aiPersona): bool
    {
        return $user->can('delete ai personas')
            || ($user->id === $aiPersona->created_by_user_id && $user->can('delete own ai personas'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AiPersona $aiPersona): bool
    {
        return $user->can('restore ai personas');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AiPersona $aiPersona): bool
    {
        return $user->can('force delete ai personas');
    }
}
