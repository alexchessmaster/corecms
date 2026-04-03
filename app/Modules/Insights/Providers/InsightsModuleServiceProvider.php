<?php

namespace App\Modules\Insights\Providers;

use Illuminate\Support\ServiceProvider;

class InsightsModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Insights/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Insights/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Insights/resources/views'), 'insights');
    }
}
