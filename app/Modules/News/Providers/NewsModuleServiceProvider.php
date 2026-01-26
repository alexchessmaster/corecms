<?php

namespace App\Modules\News\Providers;

use App\Modules\News\Models\News;
use App\Modules\News\Models\NewsAuthor;
use Illuminate\Support\ServiceProvider;
use App\Modules\News\Models\NewsCategory;
use App\Modules\News\Models\NewsTag;
use App\Modules\News\Policies\NewsPolicy;
use App\Modules\News\Observers\NewsObserver;
use App\Modules\News\Observers\NewsCategoryObserver;
use App\Modules\News\Policies\NewsAuthorPolicy;
use App\Modules\News\Policies\NewsCategoryPolicy;
use App\Modules\News\Policies\NewsTagPolicy;
use Illuminate\Support\Facades\Gate;

class NewsModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(News::class, NewsPolicy::class);
        Gate::policy(NewsCategory::class, NewsCategoryPolicy::class);
        Gate::policy(NewsTag::class, NewsTagPolicy::class);
        Gate::policy(NewsAuthor::class, NewsAuthorPolicy::class);

        $this->loadRoutesFrom(app_path('Modules/News/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/News/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/News/resources/views'), 'news');

        News::observe(NewsObserver::class);
        NewsCategory::observe(NewsCategoryObserver::class);
    }
}
