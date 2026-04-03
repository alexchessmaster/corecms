<?php

use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => [LanguageAdminMiddleware::class, 'auth', 'verified']
], function () {
//    Route::resource('templates', PageController::class); // check it later if it should be removed
});

Route::get('test', function () {
    app()->setLocale('fa');

    return 'done!';
});

require __DIR__ . '/auth.php';
