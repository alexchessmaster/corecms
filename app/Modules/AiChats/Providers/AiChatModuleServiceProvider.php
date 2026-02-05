<?php

namespace App\Modules\AiChat\Providers;

use Illuminate\Support\ServiceProvider;

class AiChatModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/AiChat/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/AiChat/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/AiChat/resources/views'), 'ai-chats');

    }
}

