<?php

namespace App\Modules\Books\Observers;

use App\Modules\Books\Models\BookGenre;
use Illuminate\Support\Str;
use App\Events\SlugChangedEvent;
use App\Models\RedirectSlugChange;
use Illuminate\Support\Facades\Auth;

class BookGenreObserver
{
    /**
     * Handle the BookGenre "creating" event.
     */
    public function creating(BookGenre $bookGenre)
    {
        $bookGenre->slug = $this->generateSlug($bookGenre);
    }

    /**
     * Handle the BookGenre "created" event.
     */
    public function created(BookGenre $bookGenre)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $bookGenre->slug,
            'type' => 'book_genre_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);
    }

    /**
     * Handle the BookGenre "updating" event.
     */
    public function updating(BookGenre $bookGenre)
    {
        if ($bookGenre->isDirty('name') || $bookGenre->isDirty('parent_id') || empty($bookGenre->slug)) {
            $bookGenre->slug = $this->generateSlug($bookGenre, $bookGenre->id);
        }
    }

    /**
     * Generate a unique slug for the book genre.
     */
    private function generateSlug(BookGenre $bookGenre, $ignoreId = null)
    {
        $slug = rtrim($this->getFullLink($bookGenre), '/');
        $slug = '/' . ltrim($slug, '/');

        // Handle duplicate slugs
        $originalSlug = $slug;
        $counter = 2;
        while (BookGenre::where('slug->' . app()->getLocale(), $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the full link based on the book genre hierarchy.
     */
    private function getFullLink($bookGenre, $link = "")
    {
        // Handle missing book genre
        if (empty($bookGenre)) {
            return "/" . $link;
        }

        $slug = Str::slug($bookGenre->name);

        // Root book genre check
        if (empty($bookGenre->parent_id)) {
            return $slug . "/" . $link;
        }

        $parentBookGenre = BookGenre::find($bookGenre->parent_id);
        // Recursive call for parent book genre
        return $this->getFullLink($parentBookGenre, $slug . "/");
    }

    /**
     * Handle the BookGenre "updated" event.
     */
    public function updated(BookGenre $bookGenre)
    {
        if ($bookGenre->isDirty('slug')) {
            if (array_key_exists(app()->getLocale(), $bookGenre->getOriginal('slug'))) {
                RedirectSlugChange::create([
                    'old_slug' => $bookGenre->getOriginal('slug')[app()->getLocale()],
                    'new_slug' => $bookGenre->slug,
                    'type' => 'book_genre_updated',
                    'user_id' => Auth::id() ?? null,
                    'language' => app()->getLocale(),
                ]);

                event(new SlugChangedEvent());
            }
        }
    }

    /**
     * Handle the BookGenre "deleted" event.
     */
    public function deleted(BookGenre $bookGenre)
    {
        RedirectSlugChange::create([
            'old_slug' => $bookGenre->slug,
            'new_slug' => $bookGenre?->parent?->slug ?? '/',
            'type' => 'book_genre_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        event(new SlugChangedEvent());
    }

    /**
     * Handle the BookGenre "restored" event.
     */
    public function restored(BookGenre $bookGenre): void
    {
        //
    }

    /**
     * Handle the BookGenre "force deleted" event.
     */
    public function forceDeleted(BookGenre $bookGenre): void
    {
        //
    }
}
