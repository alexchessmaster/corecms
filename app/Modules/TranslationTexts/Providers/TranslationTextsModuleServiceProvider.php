<?php

namespace App\Modules\TranslationTexts\Providers;

use Illuminate\Support\ServiceProvider;

class TranslationTextsModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/TranslationTexts/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/TranslationTexts/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/TranslationTexts/resources/views'), 'translation-texts');
    }
}
