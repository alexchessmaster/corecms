<?php

namespace App\Policies;

use App\Models\AiChat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AiChatPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view ai chats');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AiChat $aiChat): bool
    {
        return $user->can('view ai chats') 
            || ($aiChat->user_id === $user->id && $user->can('view own ai chats'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ai chats');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AiChat $aiChat): bool
    {
        return $user->can('edit ai chats') 
            || ($aiChat->user_id === $user->id && $user->can('edit own ai chats'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AiChat $aiChat): bool
    {
        return $user->can('delete ai chats') 
            || ($aiChat->user_id === $user->id && $user->can('delete own ai chats'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AiChat $aiChat): bool
    {
        return $user->can('restore ai chats');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AiChat $aiChat): bool
    {
        return $user->can('force delete ai chats');
    }
}
