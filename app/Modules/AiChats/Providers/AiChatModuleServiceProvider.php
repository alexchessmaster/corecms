<?php

namespace App\Modules\AiChats\Providers;

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
        $this->loadRoutesFrom(app_path('Modules/AiChats/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/AiChats/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/AiChats/resources/views'), 'ai-chats');

    }
}

