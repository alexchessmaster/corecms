<?php

namespace App\Modules\Shared\Providers;

use Illuminate\Support\ServiceProvider;

class ShareModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Share/routes/api/v2.php'));
        $this->loadRoutesFrom(app_path('Modules/Share/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Share/resources/views'), 'share');

    }
}
