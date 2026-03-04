<?php

namespace App\Modules\Products\Observers;

use App\Modules\Products\Models\Product;
use Illuminate\Support\Str;
use App\Events\SlugChangedEvent;
use App\Models\RedirectSlugChange;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "creating" event.
     */
    public function creating(Product $product): void
    {
        if (empty($product->slug)) {
            $product->slug = $this->generateSlug($product);
        }
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $product->slug,
            'type' => 'product_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);
    }

    /**
     * Handle the Product "updating" event.
     */
    public function updating(Product $product)
    {
        if ($product->isDirty('product_category_id') || $product->isDirty('title') || $product->isDirty('slug') || empty($product->slug)) {
            $product->slug = $this->generateSlug($product, $product->id);
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product)
    {
        if ($product->isDirty('slug')) {
            if (array_key_exists(app()->getLocale(), $product->getOriginal('slug'))) {
                RedirectSlugChange::create([
                    'old_slug' => $product->getOriginal('slug')[app()->getLocale()],
                    'new_slug' => $product->slug,
                    'type' => 'product_updated',
                    'user_id' => Auth::id() ?? null,
                    'language' => app()->getLocale(),
                ]);

                event(new SlugChangedEvent());
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product)
    {
        RedirectSlugChange::create([
            'old_slug' => $product->slug,
            'new_slug' => $product->category->slug,
            'type' => 'product_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        event(new SlugChangedEvent());
    }

    /**
     * Generate a unique slug for the product.
     */
    private function generateSlug(Product $product, $ignoreId = null)
    {
        $productCategoryId = $product->category->id ?? $product->product_category_id ?? null;
        if (!$productCategoryId) {
            // If no category, generate a simple slug based on title
            $slug = '/' . Str::slug($product->title);
        } else {
            // Build the full link with category
            $categorySlug = $product->category->slug ?? '';
            if ($categorySlug) {
                $link = rtrim($categorySlug, '/') . '/';
                $link = '/' . ltrim($link, '/');
                $slug = $link . Str::slug($product->title);
            } else {
                $slug = '/' . Str::slug($product->title);
            }
        }

        // Handle duplicate slugs
        $originalSlug = $slug;
        $counter = 2;

        while (Product::where('slug->' . app()->getLocale(), $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
