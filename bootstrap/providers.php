<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\ViewServiceProvider::class,

    App\Modules\Shared\Providers\SharedModuleServiceProvider::class,
    App\Modules\AiChat\Providers\AiChatModuleServiceProvider::class,
    App\Modules\Booking\Providers\BookingModuleServiceProvider::class,
    App\Modules\News\Providers\NewsModuleServiceProvider::class,
    App\Modules\Books\Providers\BooksModuleServiceProvider::class,
    App\Modules\Products\Providers\ProductsModuleServiceProvider::class,
    App\Modules\Users\Providers\UsersModuleServiceProvider::class,
    App\Modules\Articles\Providers\ArticlesModuleServiceProvider::class,
    App\Modules\Comments\Providers\CommentsModuleServiceProvider::class,
    App\Modules\Forms\Providers\FormsModuleServiceProvider::class,
    App\Modules\Insights\Providers\InsightsModuleServiceProvider::class,
    App\Modules\Languages\Providers\LanguagesModuleServiceProvider::class,
    App\Modules\Menus\Providers\MenusModuleServiceProvider::class,
    App\Modules\Pages\Providers\PagesModuleServiceProvider::class,
    App\Modules\Redirects\Providers\RedirectsModuleServiceProvider::class,
    App\Modules\Settings\Providers\SettingsModuleServiceProvider::class,
    App\Modules\TranslationTexts\Providers\TranslationTextsModuleServiceProvider::class,
    App\Modules\Widgets\Providers\WidgetsModuleServiceProvider::class,
];
