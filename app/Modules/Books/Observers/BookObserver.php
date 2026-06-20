<?php

namespace App\Modules\Books\Observers;

use App\Modules\Shared\Events\SlugChangedEvent;
use App\Modules\Redirects\Models\RedirectSlugChange;
use App\Modules\Books\Models\Book;
use App\Modules\Shared\Actions\DeleteImageAction;
use App\Modules\Shared\Helpers\FileHelper;
use App\Modules\Shared\Helpers\UrlHelper;
use App\Modules\Shared\Jobs\ProcessImageJob;
use Illuminate\Support\Facades\Auth;

class BookObserver
{
    /**
     * Handle the Book "creating" event.
     */
    public function creating(Book $book): void
    {
        if (empty($book->getTranslation('slug', app()->getLocale(), false))) {
            $slug = $this->generateSlug($book);
            if (!empty($slug)) {
                $book->setTranslation('slug', app()->getLocale(), $slug);
            }
        }
    }

    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $book->getTranslation('slug', app()->getLocale()),
            'type' => 'book_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        $newImagePath = public_path($book->getTranslation('image', app()->getLocale(), false));
        ProcessImageJob::dispatch($newImagePath);

        $imagesArr = FileHelper::getMediumThumbnailImagePaths($newImagePath);
        $book->setTranslation('image_medium', app()->getLocale(), $imagesArr['medium']);
        $book->setTranslation('image_thumbnail', app()->getLocale(), $imagesArr['thumbnail']);
        $book->saveQuietly();
    }

    /**
     * Handle the Book "updating" event.
     */
    public function updating(Book $book)
    {
        if ($book->isDirty('book_genre_id') || $book->isDirty('title') || $book->isDirty('slug') || empty($book->getTranslation('slug', app()->getLocale(), false))) {
            $slug = $this->generateSlug($book, $book->id);
            if (!empty($slug)) {
                $book->setTranslation('slug', app()->getLocale(), $slug);
            }
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
                    'new_slug' => $book->getTranslation('slug', app()->getLocale()),
                    'type' => 'book_updated',
                    'user_id' => Auth::id() ?? null,
                    'language' => app()->getLocale(),
                ]);

                event(new SlugChangedEvent());
            }
        }

        if ($book->isDirty('image')) {
            if (is_array($book->getOriginal('image')) && array_key_exists(app()->getLocale(), $book->getOriginal('image'))) {
                $oldImagePath = public_path($book->getOriginal('image')[app()->getLocale()]);
                DeleteImageAction::deleteModelImages($oldImagePath);
            }
            $newImage = $book->getTranslation('image', app()->getLocale(), false);
            if (!empty($newImage)) {
                $newImagePath = public_path($newImage);
                ProcessImageJob::dispatch($newImagePath);
                $imagesArr = FileHelper::getMediumThumbnailImagePaths($newImagePath);
                $book->setTranslation('image_medium', app()->getLocale(), $imagesArr['medium']);
                $book->setTranslation('image_thumbnail', app()->getLocale(), $imagesArr['thumbnail']);
                $book->saveQuietly();
            }
        }
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book)
    {
        RedirectSlugChange::create([
            'old_slug' => $book->getTranslation('slug', app()->getLocale()),
            'new_slug' => $book->bookGenre->getTranslation('slug', app()->getLocale()),
            'type' => 'book_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        event(new SlugChangedEvent());

        $oldImagePath = public_path($book->getTranslation('image', app()->getLocale()));
        DeleteImageAction::deleteModelImages($oldImagePath);
    }

    /**
     * Generate a unique slug for the book.
     */
    private function generateSlug(Book $book, $ignoreId = null)
    {
        $bookGenre = $book->bookGenre;
        if (!$bookGenre) {
            return null;
        }

        $oldSlug = trim($book->getTranslation('slug', app()->getLocale(), false), '/');
        $slugWithoutCategories = '';

        if (!empty($oldSlug)) {
            $parts = explode('/', $oldSlug);
            $slugWithoutCategories = end($parts);
            if (empty($slugWithoutCategories) || preg_match('/^-[0-9]+$/', $slugWithoutCategories)) {
                $slugWithoutCategories = '';
            }
        }

        if (empty($slugWithoutCategories)) {
            $title = $book->getTranslation('title', app()->getLocale(), false);
            if (empty($title)) {
                return null;
            }

            $slugWithoutCategories = UrlHelper::generateSlug($title);
            if (empty($slugWithoutCategories)) {
                return null;
            }
        }

        $link = '/' . trim($bookGenre->getTranslation('slug', app()->getLocale(), false), '/');
        $slug = rtrim($link, '/') . '/' . trim($slugWithoutCategories, '/');

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
