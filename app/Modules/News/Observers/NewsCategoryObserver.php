<?php

namespace App\Modules\News\Observers;

use Illuminate\Support\Str;
use App\Modules\News\Models\NewsCategory;
use App\Events\SlugChangedEvent;
use App\Models\RedirectSlugChange;
use Illuminate\Support\Facades\Auth;

class NewsCategoryObserver
{
    /**
     * Handle the NewsCategory "creating" event.
     */
    public function creating(NewsCategory $newsCategory)
    {
        $newsCategory->slug = $this->generateSlug($newsCategory);
    }

    /**
     * Handle the NewsCategory "created" event.
     */
    public function created(NewsCategory $newsCategory)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $newsCategory->slug,
            'type' => 'news_category_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);
    }

        /**
     * Handle the NewsCategory "updating" event.
     */
    public function updating(NewsCategory $newsCategory)
    {
        if ($newsCategory->isDirty('name') || $newsCategory->isDirty('parent_id') || empty($newsCategory->slug)) {
            $newsCategory->slug = $this->generateSlug($newsCategory, $newsCategory->id);
        }
    }

    /**
     * Generate a unique slug for the news category.
     */
    private function generateSlug(NewsCategory $newsCategory, $ignoreId = null)
    {
        $slug = rtrim($this->getFullLink($newsCategory), '/');
        $slug = '/' . ltrim($slug, '/');
        
        // Handle duplicate slugs
        $originalSlug = $slug;
        $counter = 2;
        while (NewsCategory::where('slug->' . app()->getLocale(), $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the full link based on the news category hierarchy.
     */
    private function getFullLink($newsCategory, $link = "")
    {
        // Handle missing news category
        if (empty($newsCategory)) {
            return "/" . $link;
        }

        $slug = Str::slug($newsCategory->name);

        // Root news category check
        if (empty($newsCategory->parent_id)) {
            return $slug . "/" . $link;
        }

        $parentNewsCategory = NewsCategory::find($newsCategory->parent_id);
        // Recursive call for parent news category
        return $this->getFullLink($parentNewsCategory, $slug . "/");
    }

    /**
     * Handle the NewsCategory "updated" event.
     */
    public function updated(NewsCategory $newsCategory)
    {
        if ($newsCategory->isDirty('slug')) {
            if (array_key_exists(app()->getLocale(), $newsCategory->getOriginal('slug'))) {
                RedirectSlugChange::create([
                    'old_slug' => $newsCategory->getOriginal('slug')[app()->getLocale()],
                    'new_slug' => $newsCategory->slug,
                    'type' => 'news_category_updated',
                    'user_id' => Auth::id() ?? null,
                    'language' => app()->getLocale(),
                ]);

                event(new SlugChangedEvent());
            }
        }
    }

    /**
     * Handle the NewsCategory "deleted" event.
     */
    public function deleted(NewsCategory $newsCategory)
    {
        RedirectSlugChange::create([
            'old_slug' => $newsCategory->slug,
            'new_slug' => $newsCategory->parent?->slug ?? '/',
            'type' => 'news_category_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        event(new SlugChangedEvent());
    }

    /**
     * Handle the NewsCategory "restored" event.
     */
    public function restored(NewsCategory $newsCategory): void
    {
        //
    }

    /**
     * Handle the NewsCategory "force deleted" event.
     */
    public function forceDeleted(NewsCategory $newsCategory): void
    {
        //
    }
}
