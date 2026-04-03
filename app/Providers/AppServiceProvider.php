<?php

namespace App\Providers;

use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\Category;
use App\Modules\Languages\Models\Language;
use App\Modules\Shared\Services\OpenAiService;
use App\Modules\Shared\Events\SlugChangedEvent;
use App\Modules\Articles\Observers\ArticleObserver;
use App\Modules\Articles\Observers\CategoryObserver;
use Illuminate\Support\Facades\URL;
use App\Modules\AiChat\Contracts\AiServiceInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Modules\Shared\Listeners\HandleSlugChangeListener;
use App\Modules\Languages\Repositories\LanguageRepository;
use App\Modules\Settings\Repositories\SettingRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingRepository::class, fn($app)=> new SettingRepository());
        $this->app->singleton(LanguageRepository::class, fn($app)=> new LanguageRepository());
        $this->app->bind(AiServiceInterface::class, OpenAiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');

        Model::preventLazyLoading();

        Event::listen(SlugChangedEvent::class, HandleSlugChangeListener::class);

        if ((!app()->runningInConsole()) && Schema::hasTable('languages')) {
            Language::all()->each(function ($language) {
                if ($language->default) {
                    app()->setLocale($language->code);
                }
            });
        }
    }
}
