<?php

namespace App\Modules\Books\Observers;

use App\Modules\Books\Models\Book;
use Illuminate\Support\Str;
use App\Events\SlugChangedEvent;
use App\Models\RedirectSlugChange;
use Illuminate\Support\Facades\Auth;

class BookObserver
{
    /**
     * Handle the Book "creating" event.
     */
    public function creating(Book $book): void
    {
        if (empty($book->slug)) {
            $book->slug = $this->generateSlug($book);
        }
    }

    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $book->slug,
            'type' => 'book_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);
    }

    /**
     * Handle the Book "updating" event.
     */
    public function updating(Book $book)
    {
        if ($book->isDirty('book_genre_id') || $book->isDirty('title') || $book->isDirty('slug') || empty($book->slug)) {
            $book->slug = $this->generateSlug($book, $book->id);
        }
    }


    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book)
    {
        if ($book->isDirty('slug')) {
            if(array_key_exists(app()->getLocale(), $book->getOriginal('slug'))) {
                RedirectSlugChange::create([
                    'old_slug' => $book->getOriginal('slug')[app()->getLocale()],
                    'new_slug' => $book->slug,
                    'type' => 'book_updated',
                    'user_id' => Auth::id() ?? null,
                    'language' => app()->getLocale(),
                ]);

                event(new SlugChangedEvent());
            }
        }
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book)
    {
        RedirectSlugChange::create([
            'old_slug' => $book->slug,
            'new_slug' => $book->bookGenre->slug,
            'type' => 'book_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        event(new SlugChangedEvent());
    }

    /**
     * Generate a unique slug for the book.
     */
    private function generateSlug(Book $book, $ignoreId = null)
    {
        $bookGenreId = $book->bookGenre->id ?? null;
        if (!$bookGenreId) {
            return null;
        }

        // Build the full link
        $link = rtrim($book->bookGenre->slug, '/') . '/';
        $link = '/' . ltrim($link, '/');
        $slug = $link . Str::slug($book->title);

        // Handle duplicate slugs
        $originalSlug = $slug;
        $counter = 2;

        while (Book::where('slug->' . app()->getLocale(), $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
