<?php

namespace App\Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Settings/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Settings/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Settings/resources/views'), 'settings');
    }
}
