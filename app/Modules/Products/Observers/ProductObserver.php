<?php

namespace App\Modules\Products\Observers;

use App\Modules\Shared\Events\SlugChangedEvent;
use App\Modules\Redirects\Models\RedirectSlugChange;
use App\Modules\Products\Models\Product;
use App\Modules\Shared\Actions\DeleteImageAction;
use App\Modules\Shared\Helpers\FileHelper;
use App\Modules\Shared\Helpers\UrlHelper;
use App\Modules\Shared\Jobs\ProcessImageJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Handle the Product "creating" event.
     */
    public function creating(Product $product): void
    {
        if (empty($product->getTranslation('slug', app()->getLocale(), false))) {
            $slug = $this->generateSlug($product);
            if (!empty($slug)) {
                $product->setTranslation('slug', app()->getLocale(), $slug);
            }
        }
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $product->getTranslation('slug', app()->getLocale()),
            'type' => 'product_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        $imagePath = public_path($product->getTranslation('image', app()->getLocale(), false));
        if ($imagePath && file_exists(public_path($imagePath))) {
            ProcessImageJob::dispatch($imagePath);

            $imagesArr = FileHelper::getMediumThumbnailImagePaths($imagePath);
            $product->setTranslation('image_medium', app()->getLocale(), $imagesArr['medium']);
            $product->setTranslation('image_thumbnail', app()->getLocale(), $imagesArr['thumbnail']);
            $product->saveQuietly();
        }
    }

    /**
     * Handle the Product "updating" event.
     */
    public function updating(Product $product)
    {
        if (
            $product->isDirty('product_category_id')
            || $product->isDirty('title')
            || $product->isDirty('slug')
            || empty($product->getTranslation('slug', app()->getLocale(), false))
        ) {
            $slug = $this->generateSlug($product, $product->id);
            if (!empty($slug)) {
                $product->setTranslation('slug', app()->getLocale(), $slug);
            }
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
                    'new_slug' => $product->getTranslation('slug', app()->getLocale()),
                    'type' => 'product_updated',
                    'user_id' => Auth::id() ?? null,
                    'language' => app()->getLocale(),
                ]);

                event(new SlugChangedEvent());
            }
        }

        if ($product->isDirty('image')) {
            if (is_array($product->getOriginal('image')) && array_key_exists(app()->getLocale(), $product->getOriginal('image'))) {
                $oldImagePath = public_path($product->getOriginal('image')[app()->getLocale()]);
                DeleteImageAction::deleteModelImages($oldImagePath);
            }
            $newImage = $product->getTranslation('image', app()->getLocale(), false);
            if (!empty($newImage)) {
                $newImagePath = public_path($newImage);
                ProcessImageJob::dispatch($newImagePath);
                $imagesArr = FileHelper::getMediumThumbnailImagePaths($newImagePath);
                $product->setTranslation('image_medium', app()->getLocale(), $imagesArr['medium']);
                $product->setTranslation('image_thumbnail', app()->getLocale(), $imagesArr['thumbnail']);
                $product->saveQuietly();
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product)
    {
        RedirectSlugChange::create([
            'old_slug' => $product->getTranslation('slug', app()->getLocale()),
            'new_slug' => $product->category->getTranslation('slug', app()->getLocale()),
            'type' => 'product_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        event(new SlugChangedEvent());

        $oldImagePath = public_path($product->getTranslation('image', app()->getLocale()));
        DeleteImageAction::deleteModelImages($oldImagePath);
    }

    /**
     * Generate a unique slug for the product.
     */
    private function generateSlug(Product $product, $ignoreId = null)
    {
        $productCategoryId = $product->category->id ?? $product->product_category_id ?? null;
        if (!$productCategoryId) {
            return null;
        }

        $oldSlug = trim($product->getTranslation('slug', app()->getLocale(), false), '/');
        $slugWithoutCategories = '';

        if (!empty($oldSlug)) {
            $parts = explode('/', $oldSlug);
            $slugWithoutCategories = end($parts);
            if (empty($slugWithoutCategories) || preg_match('/^-[0-9]+$/', $slugWithoutCategories)) {
                $slugWithoutCategories = '';
            }
        }

        if (empty($slugWithoutCategories)) {
            $title = $product->getTranslation('title', app()->getLocale(), false);
            if (empty($title)) {
                return null;
            }

            $slugWithoutCategories = UrlHelper::generateSlug($title);
            if (empty($slugWithoutCategories)) {
                return null;
            }
        }

        $link = '/' . trim($product->category->getTranslation('slug', app()->getLocale(), false), '/');
        $slug = rtrim($link, '/') . '/' . trim($slugWithoutCategories, '/');

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
