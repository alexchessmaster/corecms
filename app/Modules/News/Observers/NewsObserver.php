<?php

namespace App\Modules\News\Observers;

use App\Modules\News\Models\News;
use App\Modules\Shared\Events\SlugChangedEvent;
use App\Modules\Redirects\Models\RedirectSlugChange;
use App\Modules\Shared\Actions\DeleteImageAction;
use App\Modules\Shared\Helpers\FileHelper;
use App\Modules\Shared\Helpers\UrlHelper;
use App\Modules\Shared\Jobs\ProcessImageJob;
use Illuminate\Support\Facades\Auth;

class NewsObserver
{
    /**
     * Handle the News "creating" event.
     */
    public function creating(News $news): void
    {
        if (empty($news->getTranslation('slug', app()->getLocale(), false))) {
            $slug = $this->generateSlug($news);
            if (!empty($slug)) {
                $news->setTranslation('slug', app()->getLocale(), $slug);
            }
        }
    }

    /**
     * Handle the News "created" event.
     */
    public function created(News $news)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $news->getTranslation('slug', app()->getLocale()),
            'type' => 'news_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        $imagePath = public_path($news->getTranslation('image', app()->getLocale(), false));
        if ($imagePath && file_exists(public_path($imagePath))) {
            ProcessImageJob::dispatch($imagePath);

            $imagesArr = FileHelper::getMediumThumbnailImagePaths($imagePath);
            $news->setTranslation('image_medium', app()->getLocale(), $imagesArr['medium']);
            $news->setTranslation('image_thumbnail', app()->getLocale(), $imagesArr['thumbnail']);
            $news->saveQuietly();
        }
    }

    /**
     * Handle the News "updating" event.
     */
    public function updating(News $news)
    {
        if (
            $news->isDirty('news_category_id')
            || $news->isDirty('title')
            || $news->isDirty('slug')
            || empty($news->getTranslation('slug', app()->getLocale(), false))
        ) {
            $slug = $this->generateSlug($news, $news->id);
            if (!empty($slug)) {
                $news->setTranslation('slug', app()->getLocale(), $slug);
            }
        }
    }

    /**
     * Handle the News "updated" event.
     */
    public function updated(News $news)
    {
        if ($news->isDirty('slug')) {
            if (array_key_exists(app()->getLocale(), $news->getOriginal('slug'))) {
                RedirectSlugChange::create([
                    'old_slug' => $news->getOriginal('slug')[app()->getLocale()],
                    'new_slug' => $news->getTranslation('slug', app()->getLocale()),
                    'type' => 'news_updated',
                    'user_id' => Auth::id() ?? null,
                    'language' => app()->getLocale(),
                ]);

                event(new SlugChangedEvent());
            }
        }

        if ($news->isDirty('image')) {
            if (is_array($news->getOriginal('image')) && array_key_exists(app()->getLocale(), $news->getOriginal('image'))) {
                $oldImagePath = public_path($news->getOriginal('image')[app()->getLocale()]);
                DeleteImageAction::deleteModelImages($oldImagePath);
            }
            $newImage = $news->getTranslation('image', app()->getLocale(), false);
            if (!empty($newImage)) {
                $newImagePath = public_path($newImage);
                ProcessImageJob::dispatch($newImagePath);
                $imagesArr = FileHelper::getMediumThumbnailImagePaths($newImagePath);
                $news->setTranslation('image_medium', app()->getLocale(), $imagesArr['medium']);
                $news->setTranslation('image_thumbnail', app()->getLocale(), $imagesArr['thumbnail']);
                $news->saveQuietly();
            }
        }
    }

    /**
     * Handle the News "deleted" event.
     */
    public function deleted(News $news)
    {
        RedirectSlugChange::create([
            'old_slug' => $news->getTranslation('slug', app()->getLocale()),
            'new_slug' => $news->category->getTranslation('slug', app()->getLocale()) ?? '/',
            'type' => 'news_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        event(new SlugChangedEvent());

        $oldImagePath = public_path($news->getTranslation('image', app()->getLocale()));
        DeleteImageAction::deleteModelImages($oldImagePath);
    }

    /**
     * Generate a unique slug for the news.
     */
    private function generateSlug(News $news, $ignoreId = null)
    {
        $newsCategoryId = $news->category->id ?? $news->news_category_id ?? null;
        if (!$newsCategoryId) {
            return null;
        }

        $oldSlug = trim($news->getTranslation('slug', app()->getLocale(), false), '/');
        $slugWithoutCategories = '';

        if (!empty($oldSlug)) {
            $parts = explode('/', $oldSlug);
            $slugWithoutCategories = end($parts);
            if (empty($slugWithoutCategories) || preg_match('/^-[0-9]+$/', $slugWithoutCategories)) {
                $slugWithoutCategories = '';
            }
        }

        if (empty($slugWithoutCategories)) {
            $title = $news->getTranslation('title', app()->getLocale(), false);
            if (empty($title)) {
                return null;
            }

            $slugWithoutCategories = UrlHelper::generateSlug($title);
            if (empty($slugWithoutCategories)) {
                return null;
            }
        }

        $link = '/' . trim($news->category->getTranslation('slug', app()->getLocale(), false), '/');
        $slug = rtrim($link, '/') . '/' . trim($slugWithoutCategories, '/');

        $originalSlug = $slug;
        $counter = 2;

        while (News::where('slug->' . app()->getLocale(), $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
