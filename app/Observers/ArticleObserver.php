<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\RedirectSlugChange;
use Illuminate\Support\Facades\Auth;

class ArticleObserver
{
    /**
     * Handle the Article "creating" event.
     */
    public function creating(Article $article)
    {
        if (empty($article->slug)) {
            $article->slug = $this->generateSlug($article);
        }
    }

    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article)
    {
        RedirectSlugChange::create([
            'old_slug' => null,
            'new_slug' => $article->slug,
            'type' => 'article_created',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);
    }

    /**
     * Handle the Article "updating" event.
     */
    public function updating(Article $article)
    {
        if ($article->isDirty('category_id') || $article->isDirty('title') || $article->isDirty('slug') || empty($article->slug)) {
            $article->slug = $this->generateSlug($article, $article->id);
        }
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article)
    {
        if ($article->isDirty('slug')) {
            RedirectSlugChange::create([
                'old_slug' => $article->getOriginal('slug')[app()->getLocale()],
                'new_slug' => $article->slug,
                'type' => 'article_updated',
                'user_id' => Auth::id() ?? null,
                'language' => app()->getLocale(),
            ]);
        }
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

        // Build the full link
        $link = rtrim($article->category->slug, '/') . '/';
        $link = '/' . ltrim($link, '/');
        $slug = $link . Str::slug($article->title);

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

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article)
    {
        RedirectSlugChange::create([
            'old_slug' => $article->slug,
            'new_slug' => $article->category->slug,
            'type' => 'article_deleted',
            'user_id' => Auth::id() ?? null,
            'language' => app()->getLocale(),
        ]);
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        //
    }
}
