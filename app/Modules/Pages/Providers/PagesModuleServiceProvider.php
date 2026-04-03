<?php

namespace App\Modules\Pages\Providers;

use Illuminate\Support\ServiceProvider;

class PagesModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Pages/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Pages/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Pages/resources/views'), 'pages');
    }
}
