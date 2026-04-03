<?php

namespace App\Modules\Comments\Providers;

use Illuminate\Support\ServiceProvider;

class CommentsModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Comments/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Comments/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Comments/resources/views'), 'comments');

    }
}
