<?php

namespace App\Modules\Shared\Providers;

use Illuminate\Support\ServiceProvider;

class SharedModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Shared/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Shared/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Shared/resources/views'), 'shared');

    }
}
