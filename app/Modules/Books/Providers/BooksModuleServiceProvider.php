<?php

namespace App\Modules\Books\Providers;

use App\Modules\Books\Models\Book;
use App\Modules\Books\Models\BookGenre;
use App\Modules\Books\Observers\BookGenreObserver;
use App\Modules\Books\Observers\BookObserver;
use Illuminate\Support\ServiceProvider;

class BooksModuleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(app_path('Modules/Books/routes/api.php'));
        $this->loadRoutesFrom(app_path('Modules/Books/routes/web.php'));
        $this->loadViewsFrom(app_path('Modules/Books/resources/views'), 'books');

        Book::observe(BookObserver::class);
        BookGenre::observe(BookGenreObserver::class);
    }
}
