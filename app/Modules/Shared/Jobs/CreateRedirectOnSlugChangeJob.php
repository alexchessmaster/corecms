<?php

namespace App\Modules\Shared\Jobs;

use App\Models\Article;
use App\Models\Book;
use App\Models\BookGenre;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Redirect;
use App\Models\RedirectSlugChange;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateRedirectOnSlugChangeJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
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
                } elseif ($change->type === 'book_genre_updated') {
                    // Handle book genre slug update
                    $this->handleBookGenreSlugUpdate($change);
                } elseif ($change->type === 'book_genre_deleted') {
                    // Handle book genre deletion
                    $this->handleBookGenreDeletion($change);
                } elseif ($change->type === 'book_updated') {
                    // Handle book slug update
                    $this->handleBookSlugUpdate($change);
                } elseif ($change->type === 'book_deleted') {
                    // Handle book deletion
                    $this->handleBookDeletion($change);
                } elseif ($change->type === 'product_category_updated') {
                    // Handle product category slug update
                    $this->handleProductCategorySlugUpdate($change);
                } elseif ($change->type === 'product_category_deleted') {
                    // Handle product category deletion
                    $this->handleProductCategoryDeletion($change);
                } elseif ($change->type === 'product_updated') {
                    // Handle product slug update
                    $this->handleProductSlugUpdate($change);
                } elseif ($change->type === 'product_deleted') {
                    // Handle product deletion
                    $this->handleProductDeletion($change);
                } elseif ($change->type === 'page_updated') {
                    // Handle page slug update
                    $this->handlePageSlugUpdate($change);
                } elseif ($change->type === 'page_deleted') {
                    // Handle page deletion
                    $this->handlePageDeletion($change);
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

    /**
     * Handle book genre slug update.
     *
     * @param RedirectSlugChange $change
     */
    private function handleBookGenreSlugUpdate(RedirectSlugChange $change)
    {
        $books = Book::where('slug->' . $change->language, 'LIKE', $change->old_slug . '%')->get();
        foreach ($books as $book) {
            $oldBookSlug = $book->slug;
            $newBookSlug = str_replace($change->old_slug, $change->new_slug, $book->slug);

            // Update book slug
            $book->setTranslation('slug', $change->language, $newBookSlug);
            $book->save();

            // Create redirect for the book
            Redirect::create([
                'from' => $oldBookSlug,
                'to' => $newBookSlug,
                'language' => $change->language,
            ]);
        }

        // Create redirect for the book genre
        Redirect::create([
            'from' => $change->old_slug,
            'to' => $change->new_slug,
            'language' => $change->language,
        ]);
    }

    /**
     * Handle book genre deletion.
     *
     * @param RedirectSlugChange $change
     */
    private function handleBookGenreDeletion(RedirectSlugChange $change)
    {
        $books = Book::where('slug->' . $change->language, 'LIKE', $change->old_slug . '%')->get();
        foreach ($books as $book) {
            $oldBookSlug = $book->slug;

            if ($change->new_slug === '/') {
                $newBookSlug = str_replace($change->old_slug, '/uncategorized', $oldBookSlug);
            } else {
                $newBookSlug = str_replace($change->old_slug, $change->new_slug, $oldBookSlug);
            }

            // Update book slug
            $book->setTranslation('slug', $change->language, $newBookSlug);
            $book->save();

            Redirect::create([
                'from' => $oldBookSlug,
                'to' => $newBookSlug,
                'language' => $change->language,
            ]);
        }

        // Create a redirect for the book genre itself to a placeholder or 404 page
        Redirect::create([
            'from' => $change->old_slug,
            'to' => $change->new_slug, // Same as above
            'language' => $change->language,
        ]);
    }

    /**
     * Handle book slug update.
     *
     * @param RedirectSlugChange $change
     */
    private function handleBookSlugUpdate(RedirectSlugChange $change)
    {
        // Directly create a redirect for the book
        Redirect::create([
            'from' => $change->old_slug,
            'to' => $change->new_slug,
            'language' => $change->language,
        ]);
    }

    /**
     * Handle book deletion.
     *
     * @param RedirectSlugChange $change
     */
    private function handleBookDeletion(RedirectSlugChange $change)
    {
        // Create a redirect for the deleted book
        Redirect::create([
            'from' => $change->old_slug,
            'to' => '/410', // Redirect to a custom 404 page or another fallback
            'language' => $change->language,
        ]);
    }

    /**
     * Handle product category slug update.
     *
     * @param RedirectSlugChange $change
     */
    private function handleProductCategorySlugUpdate(RedirectSlugChange $change)
    {
        $products = Product::where('slug->' . $change->language, 'LIKE', $change->old_slug . '%')->get();
        foreach ($products as $product) {
            $oldProductSlug = $product->slug;
            $newProductSlug = str_replace($change->old_slug, $change->new_slug, $product->slug);

            // Update product slug
            $product->setTranslation('slug', $change->language, $newProductSlug);
            $product->save();

            // Create redirect for the product
            Redirect::create([
                'from' => $oldProductSlug,
                'to' => $newProductSlug,
                'language' => $change->language,
            ]);
        }

        // Create redirect for the product category
        Redirect::create([
            'from' => $change->old_slug,
            'to' => $change->new_slug,
            'language' => $change->language,
        ]);
    }

    /**
     * Handle product category deletion.
     *
     * @param RedirectSlugChange $change
     */
    private function handleProductCategoryDeletion(RedirectSlugChange $change)
    {
        $products = Product::where('slug->' . $change->language, 'LIKE', $change->old_slug . '%')->get();
        foreach ($products as $product) {
            $oldProductSlug = $product->slug;

            if ($change->new_slug === '/') {
                $newProductSlug = str_replace($change->old_slug, '/uncategorized', $oldProductSlug);
            } else {
                $newProductSlug = str_replace($change->old_slug, $change->new_slug, $oldProductSlug);
            }

            // Update product slug
            $product->setTranslation('slug', $change->language, $newProductSlug);
            $product->save();

            Redirect::create([
                'from' => $oldProductSlug,
                'to' => $newProductSlug,
                'language' => $change->language,
            ]);
        }

        // Create a redirect for the product category itself to a placeholder or 404 page
        Redirect::create([
            'from' => $change->old_slug,
            'to' => $change->new_slug,
            'language' => $change->language,
        ]);
    }

    /**
     * Handle product slug update.
     *
     * @param RedirectSlugChange $change
     */
    private function handleProductSlugUpdate(RedirectSlugChange $change)
    {
        // Directly create a redirect for the product
        Redirect::create([
            'from' => $change->old_slug,
            'to' => $change->new_slug,
            'language' => $change->language,
        ]);
    }

    /**
     * Handle product deletion.
     *
     * @param RedirectSlugChange $change
     */
    private function handleProductDeletion(RedirectSlugChange $change)
    {
        // Create a redirect for the deleted product
        Redirect::create([
            'from' => $change->old_slug,
            'to' => '/410', // Redirect to a custom 404 page or another fallback
            'language' => $change->language,
        ]);
    }

    /**
     * Handle page slug update.
     *
     * @param RedirectSlugChange $change
     */
    private function handlePageSlugUpdate(RedirectSlugChange $change)
    {
        // Directly create a redirect for the page
        Redirect::create([
            'from' => $change->old_slug,
            'to' => $change->new_slug,
            'language' => $change->language,
        ]);
    }

    /**
     * Handle page deletion.
     *
     * @param RedirectSlugChange $change
     */
    private function handlePageDeletion(RedirectSlugChange $change)
    {
        // Create a redirect for the deleted page
        Redirect::create([
            'from' => $change->old_slug,
            'to' => '/410', // Redirect to a custom 410 page or another fallback
            'language' => $change->language,
        ]);
    }
}
