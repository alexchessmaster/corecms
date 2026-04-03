<?php

namespace App\Modules\Menus\Providers;

use Illuminate\Support\ServiceProvider;

class MenusModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Menus/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Menus/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Menus/resources/views'), 'menus');
    }
}
