<?php

namespace App\Modules\News\Observers;

use App\Modules\News\Models\News;
use App\Events\SlugChangedEvent;
use App\Models\RedirectSlugChange;
use App\Modules\Shared\Helpers\UrlHelper;
use Illuminate\Support\Facades\Auth;

class NewsObserver
{
    /**
     * Handle the News "creating" event.
     */
    public function creating(News $news): void
    {
        if (empty($news->getTranslation('slug', app()->getLocale()))) {
            $news->setTranslation('slug', app()->getLocale(), $this->generateSlug($news));
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
    }

    /**
     * Handle the News "updating" event.
     */
    public function updating(News $news)
    {
        if ($news->isDirty('news_category_id') || $news->isDirty('title') || $news->isDirty('slug') || empty($news->getTranslation('slug', app()->getLocale()))) {
            $news->setTranslation('slug', app()->getLocale(), $this->generateSlug($news, $news->id));
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

        // Keep the last part of the url
        $oldSlug = $news->getTranslation('slug', app()->getLocale());
        $parts = explode('/', $oldSlug);
        $slugWithoutCategories = end($parts);
        if(empty($slugWithoutCategories)){
            $slugWithoutCategories = UrlHelper::generateSlug($news->getTranslation('title', app()->getLocale(), false));
        }

        // Build the full link
        $link = rtrim($news->category->getTranslation('slug', app()->getLocale()), '/') . '/';
        $link = '/' . ltrim($link, '/');
        $slug = $link . $slugWithoutCategories;     

        // Handle duplicate slugs
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
