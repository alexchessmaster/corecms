<?php

namespace App\Providers;

use App\Models\Language;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
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
        if((!app()->runningInConsole()) && Schema::hasTable('languages')){
            $languages = Language::all();
            // dd($languages);
            // Share the $languages variable with all views
            \View::share('languages', $languages);
            $settings = Setting::all();
            \View::share('settings', $settings);
        }
    }
}
