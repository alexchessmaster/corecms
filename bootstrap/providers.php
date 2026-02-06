<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\ViewServiceProvider::class,

    App\Modules\News\Providers\NewsModuleServiceProvider::class,
    App\Modules\AiChats\Providers\AiChatModuleServiceProvider::class,
];
