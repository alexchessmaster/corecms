<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Events\SlugChangedEvent;
use App\Models\RedirectSlugChange;
use Illuminate\Support\Facades\Auth;

class CategoryObserver
{
    /**
     * Handle the Article "creating" event.
     */
    public function creating(Category $category)
    {
        $category->slug = $this->generateSlug($category);
    }

    /**
     * Handle the Article "created" event.
     */
    public function created(Category $category)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $category->slug,
            'type' => 'category_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);
    }

    /**
     * Handle the category "updating" event.
     */
    public function updating(Category $category)
    {
        if ($category->isDirty('name') || $category->isDirty('parent_id') || empty($category->slug)) {
            $category->slug = $this->generateSlug($category, $category->id);
        }
    }

    /**
     * Generate a unique slug for the article.
     */
    private function generateSlug(Category $category, $ignoreId = null)
    {
        $slug = rtrim($this->getFullLink($category), '/');
        $slug = '/' . ltrim($slug, '/');
        // Handle duplicate slugs
        $originalSlug = $slug;
        $counter = 2;
        while (Category::where('slug->' . app()->getLocale(), $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the full link based on the category hierarchy.
     */
    private function getFullLink($category, $link = "")
    {
        // Handle missing category
        if (empty($category)) {
            return "/" . $link;
        }
        $slug = Str::slug($category->name);
        // Root category check
        if (empty($category->parent_id)) {
            return $slug . "/" . $link;
        }
        $parentCategory = Category::find($category->parent_id);
        // Recursive call for parent category
        return $this->getFullLink($parentCategory, $slug . "/");
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Category $category)
    {
        if ($category->isDirty('slug')) {
            if (array_key_exists(app()->getLocale(), $category->getOriginal('slug'))) {


                RedirectSlugChange::create([
                    'old_slug' => $category->getOriginal('slug')[app()->getLocale()],
                    'new_slug' => $category->slug,
                    'type' => 'category_updated',
                    'user_id' => Auth::id() ?? null,
                    'language' => app()->getLocale(),
                ]);

                event(new SlugChangedEvent());
            }
        }
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Category $category)
    {
        RedirectSlugChange::create([
            'old_slug' => $category->slug,
            'new_slug' => $category?->parent?->slug ?? '/',
            'type' => 'category_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        event(new SlugChangedEvent());
    }
    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
