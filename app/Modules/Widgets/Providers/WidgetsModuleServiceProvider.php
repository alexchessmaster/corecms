<?php

namespace App\Modules\Widgets\Providers;

use Illuminate\Support\ServiceProvider;

class WidgetsModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Widgets/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Widgets/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Widgets/resources/views'), 'widgets');
    }
}
