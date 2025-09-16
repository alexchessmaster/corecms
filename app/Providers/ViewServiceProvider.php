<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        // if(app()->runningInConsole()) {
        //     return;
        // }

        $languages = Language::all();
        // Share the $languages variable with all views
        \View::share('languages', $languages);
    }
}
