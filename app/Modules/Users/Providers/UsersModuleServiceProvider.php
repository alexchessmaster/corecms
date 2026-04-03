<?php

namespace App\Modules\Users\Providers;

use Illuminate\Support\ServiceProvider;

class UsersModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Users/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Users/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Users/resources/views'), 'users');
    }
}
