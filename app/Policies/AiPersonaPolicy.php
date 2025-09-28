<?php

namespace App\Policies;

use App\Models\AiPersona;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AiPersonaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AiPersona $aiPersona): bool
    {
        return in_array($user->role, ['admin', 'editor']) || $user->id === $aiPersona->created_by_user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AiPersona $aiPersona): bool
    {
        return in_array($user->role, ['admin', 'editor']) || $user->id === $aiPersona->created_by_user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AiPersona $aiPersona): bool
    {
        return in_array($user->role, ['admin', 'editor']) || $user->id === $aiPersona->created_by_user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AiPersona $aiPersona): bool
    {
        return in_array($user->role, ['admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AiPersona $aiPersona): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }
}
