<?php

namespace App\Observers;

use App\Models\Book;
use App\Events\SlugChangedEvent;
use Illuminate\Support\Str;

class BookObserver
{
    /**
     * Handle the Book "creating" event.
     */
    public function creating(Book $book): void
    {
        $this->setSlug($book);
    }

    /**
     * Handle the Book "updating" event.
     */
    public function updating(Book $book): void
    {
        $this->setSlug($book);
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        if ($book->wasChanged('slug')) {
            event(new SlugChangedEvent($book));
        }
    }

    /**
     * Set the slug for the book based on the title.
     */
    private function setSlug(Book $book): void
    {
        $translations = $book->getTranslations('title');
        $slugTranslations = [];

        foreach ($translations as $locale => $title) {
            if (!empty($title)) {
                $baseSlug = Str::slug($title);
                $slug = $baseSlug;
                $counter = 1;

                // Check for existing slugs to ensure uniqueness
                while (Book::where('id', '!=', $book->id ?? 0)
                    ->whereJsonContains("slug->{$locale}", $slug)
                    ->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $slugTranslations[$locale] = $slug;
            }
        }

        if (!empty($slugTranslations)) {
            $book->slug = $slugTranslations;
        }
    }
}
