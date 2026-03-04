<?php

namespace App\Modules\Products\Providers;

use App\Modules\Products\Models\Product;
use App\Modules\Products\Models\ProductCategory;
use App\Modules\Products\Observers\ProductCategoryObserver;
use App\Modules\Products\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;

class ProductsModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Products/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Products/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Products/resources/views'), 'products');

        Product::observe(ProductObserver::class);
        ProductCategory::observe(ProductCategoryObserver::class);
    }
}
