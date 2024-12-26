<?php

namespace App\Providers;

use App\Events\SlugChangedEvent;
use App\Listeners\HandleSlugChangeListener;
use App\Models\Article;
use App\Models\Category;
use App\Observers\ArticleObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

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

        Event::listen(SlugChangedEvent::class, HandleSlugChangeListener::class);
    }
}
