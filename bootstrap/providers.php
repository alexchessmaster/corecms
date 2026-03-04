<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\ViewServiceProvider::class,

    App\Modules\AiChat\Providers\AiChatModuleServiceProvider::class,
    App\Modules\Booking\Providers\BookingModuleServiceProvider::class,
    App\Modules\News\Providers\NewsModuleServiceProvider::class,
    App\Modules\Books\Providers\BooksModuleServiceProvider::class,
];
