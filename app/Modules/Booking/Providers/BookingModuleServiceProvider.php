<?php


namespace App\Modules\Booking\Providers;

use Illuminate\Support\ServiceProvider;

class BookingModuleServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(app_path('Modules/AiChat/resources/views'), 'bookings');
    }
}

