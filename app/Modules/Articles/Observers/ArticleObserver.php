<?php

namespace App\Modules\Articles\Observers;

use App\Modules\Shared\Events\SlugChangedEvent;
use App\Modules\Articles\Models\Article;
use App\Modules\Redirects\Models\RedirectSlugChange;
use App\Modules\Shared\Actions\DeleteImageAction;
use App\Modules\Shared\Helpers\FileHelper;
use App\Modules\Shared\Helpers\UrlHelper;
use App\Modules\Shared\Jobs\ProcessImageJob;
use Illuminate\Support\Facades\Auth;

class ArticleObserver
{
    /**
     * Handle the Article "creating" event.
     */
    public function creating(Article $article)
    {
        if (empty($article->getTranslation('slug', app()->getLocale(), false))) {
            $article->setTranslation('slug', app()->getLocale(), $this->generateSlug($article));
        }
    }

    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $article->getTranslation('slug', app()->getLocale(), false),
            'type' => 'article_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        $newImagePath = public_path($article->getTranslation('image', app()->getLocale(), false));
        ProcessImageJob::dispatch($newImagePath);

        $imagesArr = FileHelper::getMediumThumbnailImagePaths($newImagePath);
        $article->setTranslation('image_medium', app()->getLocale(), $imagesArr['medium']);
        $article->setTranslation('image_thumbnail', app()->getLocale(), $imagesArr['thumbnail']);
        $article->saveQuietly();
    }

    /**
     * Handle the Article "updating" event.
     */
    public function updating(Article $article)
    {
        if (
            $article->isDirty('category_id')
            || $article->isDirty('title')
            || $article->isDirty('slug')
            || empty($article->getTranslation('slug', app()->getLocale(), false))
        ) {
            $article->setTranslation('slug', app()->getLocale(), $this->generateSlug($article, $article->id));
        }
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article)
    {
        if ($article->isDirty('slug')) {
            if (array_key_exists(app()->getLocale(), $article->getOriginal('slug'))) {
                RedirectSlugChange::create([
                    'old_slug' => $article->getOriginal('slug')[app()->getLocale()],
                    'new_slug' => $article->getTranslation('slug', app()->getLocale()),
                    'type' => 'article_updated',
                    'user_id' => Auth::id() ?? null,
                    'language' => app()->getLocale(),
                ]);

                event(new SlugChangedEvent());
            }
        }

        if ($article->isDirty('image')) {
            if (is_array($article->getOriginal('image')) && array_key_exists(app()->getLocale(), $article->getOriginal('image'))) {
                $oldImagePath = public_path($article->getOriginal('image')[app()->getLocale()]);
                DeleteImageAction::deleteModelImages($oldImagePath);
            }
            $newImage = $article->getTranslation('image', app()->getLocale(), false);
            if (!empty($newImage)) {
                $newImagePath = public_path($newImage);
                ProcessImageJob::dispatch($newImagePath);
                $imagesArr = FileHelper::getMediumThumbnailImagePaths($newImagePath);
                $article->setTranslation('image_medium', app()->getLocale(), $imagesArr['medium']);
                $article->setTranslation('image_thumbnail', app()->getLocale(), $imagesArr['thumbnail']);
                $article->saveQuietly();
            }
        }
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article)
    {
        RedirectSlugChange::create([
            'old_slug' => $article->getTranslation('slug', app()->getLocale()),
            'new_slug' => $article->category->getTranslation('slug', app()->getLocale()),
            'type' => 'article_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);

        event(new SlugChangedEvent());

        $oldImagePath = public_path($article->getTranslation('image', app()->getLocale()));
        DeleteImageAction::deleteModelImages($oldImagePath);
    }

    /**
     * Generate a unique slug for the article.
     */
    private function generateSlug(Article $article, $ignoreId = null)
    {
        $categoryId = $article->category->id ?? null;
        if (!$categoryId) {
            return null;
        }

        // Keep the last part of the url
        $oldSlug = $article->getTranslation('slug', app()->getLocale(), false);
        $parts = explode('/', $oldSlug);
        $slugWithoutCategories = end($parts);
        if (empty($slugWithoutCategories)) {
            $slugWithoutCategories = UrlHelper::generateSlug($article->getTranslation('title', app()->getLocale(), false));
        }

        // Build the full link
        $link = rtrim($article->category->getTranslation('slug', app()->getLocale(), false), '/') . '/';
        $link = '/' . ltrim($link, '/');
        $slug = $link . $slugWithoutCategories;

        // Handle duplicate slugs
        $originalSlug = $slug;
        $counter = 2;

        while (Article::where('slug->' . app()->getLocale(), $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
