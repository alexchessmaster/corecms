<?php

namespace App\Policies;

use App\Models\BookAuthor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookAuthorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view book authors') || $user->can('view own book authors');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BookAuthor $bookAuthor): bool
    {
        return $user->can('view book authors') || ($bookAuthor->user_id === $user->id && $user->can('view own book authors'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create book authors');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BookAuthor $bookAuthor): bool
    {
        return $user->can('edit book authors') || ($bookAuthor->user_id === $user->id && $user->can('edit own book authors'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BookAuthor $bookAuthor): bool
    {
        return $user->can('delete book authors') || ($bookAuthor->user_id === $user->id && $user->can('delete own book authors'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BookAuthor $bookAuthor): bool
    {
        return $user->can('restore book authors');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BookAuthor $bookAuthor): bool
    {
        return $user->can('force delete book authors');
    }
}
