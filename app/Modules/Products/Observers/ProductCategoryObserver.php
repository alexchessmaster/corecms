<?php

namespace App\Modules\Products\Observers;

use Illuminate\Support\Str;
use App\Modules\Products\Models\ProductCategory;
use App\Events\SlugChangedEvent;
use App\Models\RedirectSlugChange;
use Illuminate\Support\Facades\Auth;

class ProductCategoryObserver
{
    /**
     * Handle the ProductCategory "creating" event.
     */
    public function creating(ProductCategory $productCategory)
    {
        $productCategory->slug = $this->generateSlug($productCategory);
    }

    /**
     * Handle the ProductCategory "created" event.
     */
    public function created(ProductCategory $productCategory)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $productCategory->slug,
            'type' => 'product_category_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);
    }

        /**
     * Handle the ProductCategory "updating" event.
     */
    public function updating(ProductCategory $productCategory)
    {
        if ($productCategory->isDirty('name') || $productCategory->isDirty('parent_id') || empty($productCategory->slug)) {
            $productCategory->slug = $this->generateSlug($productCategory, $productCategory->id);
        }
    }

    /**
     * Generate a unique slug for the product category.
     */
    private function generateSlug(ProductCategory $productCategory, $ignoreId = null)
    {
        $slug = rtrim($this->getFullLink($productCategory), '/');
        $slug = '/' . ltrim($slug, '/');

        // Handle duplicate slugs
        $originalSlug = $slug;
        $counter = 2;
        while (ProductCategory::where('slug->' . app()->getLocale(), $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the full link based on the product category hierarchy.
     */
    private function getFullLink($productCategory, $link = "")
    {
        // Handle missing product category
        if (empty($productCategory)) {
            return "/" . $link;
        }

        $slug = Str::slug($productCategory->name);

        // Root product category check
        if (empty($productCategory->parent_id)) {
            return $slug . "/" . $link;
        }

        $parentProductCategory = ProductCategory::find($productCategory->parent_id);
        // Recursive call for parent product category
        return $this->getFullLink($parentProductCategory, $slug . "/");
    }

    /**
     * Handle the ProductCategory "updated" event.
     */
    public function updated(ProductCategory $productCategory)
    {
        if ($productCategory->isDirty('slug')) {
            if (array_key_exists(app()->getLocale(), $productCategory->getOriginal('slug'))) {
                RedirectSlugChange::create([
                    'old_slug' => $productCategory->getOriginal('slug')[app()->getLocale()],
                    'new_slug' => $productCategory->slug,
                    'type' => 'product_category_updated',
                    'user_id' => Auth::id() ?? null,
                    'language' => app()->getLocale(),
                ]);

                event(new SlugChangedEvent());
            }
        }
    }

    /**
     * Handle the ProductCategory "deleted" event.
     */
    public function deleted(ProductCategory $productCategory)
    {
        RedirectSlugChange::create([
            'old_slug' => $productCategory->slug,
            'new_slug' => $productCategory->parent?->slug ?? '/',
            'type' => 'product_category_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        event(new SlugChangedEvent());
    }

    /**
     * Handle the ProductCategory "restored" event.
     */
    public function restored(ProductCategory $productCategory): void
    {
        //
    }

    /**
     * Handle the ProductCategory "force deleted" event.
     */
    public function forceDeleted(ProductCategory $productCategory): void
    {
        //
    }
}
