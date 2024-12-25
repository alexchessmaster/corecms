<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Events\SlugChangedEvent;
use App\Observers\ArticleObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
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
        // Model::preventLazyLoading();

        Article::observe(ArticleObserver::class);
        Category::observe(CategoryObserver::class);

        Event::listen(
            SlugChangedEvent::class,
            HandleSlugChangeListener::class,
        );

        // Event::listen(
        //     \App\Events\E1::class,
        //     [
        //         \App\Listeners\L1::class,
        //         \App\Listeners\L2::class,
        //     ]
        // );

        // Event::listen(
        //     \App\Events\E2::class,
        //     [
        //         \App\Listeners\L1::class,
        //         \App\Listeners\L3::class,
        //     ]
        // );

        // // Registering a single listener to multiple events
        // Event::listen(
        //     [\App\Events\E1::class, \App\Events\E2::class],
        //     \App\Listeners\L1::class
        // );
    }
}
