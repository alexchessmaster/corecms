<?php

namespace App\Modules\News\Observers;

use App\Modules\News\Models\News;
use Illuminate\Support\Str;
use App\Events\SlugChangedEvent;
use App\Models\RedirectSlugChange;
use Illuminate\Support\Facades\Auth;

class NewsObserver
{
    /**
     * Handle the News "creating" event.
     */
    public function creating(News $news): void
    {
        if (empty($news->slug)) {
            $news->slug = $this->generateSlug($news);
        }
    }

    /**
     * Handle the News "created" event.
     */
    public function created(News $news)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $news->slug,
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
        if ($news->isDirty('news_category_id') || $news->isDirty('title') || $news->isDirty('slug') || empty($news->slug)) {
            $news->slug = $this->generateSlug($news, $news->id);
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
                    'new_slug' => $news->slug,
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
            'old_slug' => $news->slug,
            'new_slug' => $news->category->slug ?? '/',
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
            // If no category, generate a simple slug based on title
            $slug = '/' . Str::slug($news->title);
        } else {
            // Build the full link with category
            $categorySlug = $news->category->slug ?? '';
            if ($categorySlug) {
                $link = rtrim($categorySlug, '/') . '/';
                $link = '/' . ltrim($link, '/');
                $slug = $link . Str::slug($news->title);
            } else {
                $slug = '/' . Str::slug($news->title);
            }
        }

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
