<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;

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
     * Handle the Article "updating" event.
     */
    public function updating(Article $article)
    {
        if ($article->isDirty('title') || $article->isDirty('slug') || empty($article->slug)) {
            $article->slug = $this->generateSlug($article, $article->id);
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
        $link = rtrim($this->getFullLink($categoryId), '/') . '/';
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
     * Get the full link based on the category hierarchy.
     */
    private function getFullLink($categoryId, $link = "")
    {
        $category = Category::find($categoryId);

        // Handle missing category
        if (!$category) {
            return "/" . $link;
        }

        // Root category check
        if (empty($category->parent_id)) {
            return $category->slug . "/" . $link;
        }

        // Recursive call for parent category
        return $this->getFullLink($category->parent_id, $category->slug . "/");
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        //
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
