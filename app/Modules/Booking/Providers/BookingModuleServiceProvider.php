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
        $this->loadRoutesFrom(app_path('Modules/Booking/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Booking/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Booking/resources/views'), 'bookings');
    }
}

