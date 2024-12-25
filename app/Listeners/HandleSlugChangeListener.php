<?php

namespace App\Listeners;

use App\Models\Article;
use App\Models\Redirect;
use App\Events\SlugChangedEvent;
use App\Models\RedirectSlugChange;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleSlugChangeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch unchecked slug changes
        $slugChanges = RedirectSlugChange::where('checked', false)->get();

        foreach ($slugChanges as $change) {
            DB::transaction(function () use ($change) {
                if ($change->type === 'category_updated') {
                    // Handle category slug update
                    $this->handleCategorySlugUpdate($change);
                } elseif ($change->type === 'article_updated') {
                    // Handle article slug update
                    $this->handleArticleSlugUpdate($change);
                } elseif ($change->type === 'category_deleted') {
                    // Handle category deletion
                    $this->handleCategoryDeletion($change);
                } elseif ($change->type === 'article_deleted') {
                    // Handle article deletion
                    $this->handleArticleDeletion($change);
                }

                // Mark as checked
                $change->update(['checked' => true]);
            });
        }
    }

    /**
     * Handle category slug update.
     *
     * @param RedirectSlugChange $change
     */
    private function handleCategorySlugUpdate(RedirectSlugChange $change)
    {
        $articles = Article::where('slug->' . $change->language, 'LIKE', $change->old_slug . '%')->get();
        foreach ($articles as $article) {
            $oldArticleSlug = $article->slug;
            $newArticleSlug = str_replace($change->old_slug, $change->new_slug, $article->slug);

            // Update article slug
            $article->setTranslation('slug', $change->language, $newArticleSlug);
            $article->save();

            // Create redirect for the article
            Redirect::create([
                'from' => $oldArticleSlug,
                'to' => $newArticleSlug,
                'language' => $change->language,
            ]);
        }

        // Create redirect for the category
        Redirect::create([
            'from' => $change->old_slug,
            'to' => $change->new_slug,
            'language' => $change->language,
        ]);
    }

    /**
     * Handle article slug update.
     *
     * @param RedirectSlugChange $change
     */
    private function handleArticleSlugUpdate(RedirectSlugChange $change)
    {
        // Directly create a redirect for the article
        Redirect::create([
            'from' => $change->old_slug,
            'to' => $change->new_slug,
            'language' => $change->language,
        ]);
    }

    /**
     * Handle category deletion.
     *
     * @param RedirectSlugChange $change
     */
    private function handleCategoryDeletion(RedirectSlugChange $change)
    {
        $articles = Article::where('slug->' . $change->language, 'LIKE', $change->old_slug . '%')->get();
        foreach ($articles as $article) {
            $oldArticleSlug = $article->slug;

            if ($change->new_slug === '/') {
                $newArticleSlug = str_replace($change->old_slug, '/uncategorized', $oldArticleSlug);
            } else {
                $newArticleSlug = str_replace($change->old_slug, $change->new_slug, $oldArticleSlug);
            }

            // Update article slug
            $article->setTranslation('slug', $change->language, $newArticleSlug);
            $article->save();

            Redirect::create([
                'from' => $oldArticleSlug,
                'to' => $newArticleSlug, // You might redirect to a custom 404 page or the home page
                'language' => $change->language,
            ]);
        }

        // Create a redirect for the category itself to a placeholder or 404 page
        Redirect::create([
            'from' => $change->old_slug,
            'to' => $change->new_slug, // Same as above
            'language' => $change->language,
        ]);
    }

    /**
     * Handle article deletion.
     *
     * @param RedirectSlugChange $change
     */
    private function handleArticleDeletion(RedirectSlugChange $change)
    {
        // Create a redirect for the deleted article
        Redirect::create([
            'from' => $change->old_slug,
            'to' => '/410', // Redirect to a custom 404 page or another fallback
            'language' => $change->language,
        ]);
    }

    
}
