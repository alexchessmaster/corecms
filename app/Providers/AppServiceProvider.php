<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Article;
use App\Models\Product;
use App\Models\Category;
use App\Models\Language;
use App\Models\BookGenre;
use App\Models\ProductCategory;
use App\Observers\BookObserver;
use App\Events\SlugChangedEvent;
use App\Observers\ArticleObserver;
use App\Observers\ProductObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\URL;
use App\Observers\BookGenreObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Observers\ProductCategoryObserver;
use App\Listeners\HandleSlugChangeListener;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');

        Model::preventLazyLoading();

        Article::observe(ArticleObserver::class);
        Book::observe(BookObserver::class);
        BookGenre::observe(BookGenreObserver::class);
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
        ProductCategory::observe(ProductCategoryObserver::class);

        Event::listen(SlugChangedEvent::class, HandleSlugChangeListener::class);

        // if(app()->runningInConsole()) {
        //     return;
        // }

        Language::all()->each(function ($language) {
            if ($language->default) {
                app()->setLocale($language->code);
            }
        });
    }
}
