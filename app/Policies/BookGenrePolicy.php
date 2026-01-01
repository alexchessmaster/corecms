<?php

namespace App\Policies;

use App\Models\BookGenre;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookGenrePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view book genres') || $user->can('view book genres');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BookGenre $bookGenre): bool
    {
        return $user->can('view book genres') 
            || ($bookGenre->user_id === $user->id && $user->can('view own book genres'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create book genres');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BookGenre $bookGenre): bool
    {
        return $user->can('edit book genres') 
            || ($bookGenre->user_id === $user->id && $user->can('edit own book genres'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BookGenre $bookGenre): bool
    {
        return $user->can('delete book genres') 
            || ($bookGenre->user_id === $user->id && $user->can('delete own book genres'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BookGenre $bookGenre): bool
    {
        return $user->can('restore book genres');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BookGenre $bookGenre): bool
    {
        return $user->can('force delete book genres');
    }
}
