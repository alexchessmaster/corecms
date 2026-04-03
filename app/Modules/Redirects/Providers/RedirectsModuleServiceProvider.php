<?php

namespace App\Modules\Redirects\Providers;

use Illuminate\Support\ServiceProvider;

class RedirectsModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Redirects/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Redirects/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Redirects/resources/views'), 'redirects');
    }
}
