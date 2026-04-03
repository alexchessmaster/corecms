<?php

namespace App\Modules\Forms\Providers;

use Illuminate\Support\ServiceProvider;

class FormsModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Forms/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Forms/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Forms/resources/views'), 'forms');
    }
}
