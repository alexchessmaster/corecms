<?php

namespace App\Modules\Articles\Providers;

use Illuminate\Support\ServiceProvider;

class ArticlesModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Articles/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Articles/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Articles/resources/views'), 'articles');
    }
}
