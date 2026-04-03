<?php

namespace App\Modules\Languages\Providers;

use Illuminate\Support\ServiceProvider;

class LanguagesModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Languages/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Languages/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Languages/resources/views'), 'languages');
    }
}
