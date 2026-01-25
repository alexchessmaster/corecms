<?php

namespace App\Modules\News\Providers;

use App\Modules\News\Models\News;
use App\Modules\News\Models\NewsCategory;
use App\Modules\News\Observers\NewsCategoryObserver;
use App\Modules\News\Observers\NewsObserver;
use Illuminate\Support\ServiceProvider;

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
        $this->loadRoutesFrom(app_path('Modules/News/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/News/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/News/resources/views'), 'news');

        News::observe(NewsObserver::class);
        NewsCategory::observe(NewsCategoryObserver::class);
    }
}
