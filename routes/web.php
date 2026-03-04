<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UrlLogController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CommentableController;
use App\Http\Middleware\LanguageAdminMiddleware;
use App\Http\Controllers\TranslationTextController;

Route::get('/', function () {
    abort(403);
});

Route::get('admin', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified']);

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => [LanguageAdminMiddleware::class, 'auth', 'verified']
], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    Route::resource('menus', MenuController::class);
    Route::resource('upload', UploadController::class);
    Route::resource('settings', SettingController::class);
    Route::resource('pages', PageController::class);
    Route::resource('widgets', WidgetController::class);
    Route::post('widgets/sort', [WidgetController::class, 'sort'])->name('widgets.sort');
    Route::resource('fields', FieldController::class);
    Route::resource('users', UserController::class);
    Route::resource('templates', PageController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('articles', ArticleController::class);
    Route::resource('comments', CommentableController::class);
    Route::resource('tags', TagController::class);
    Route::resource('redirects', RedirectController::class);
    Route::get('url-logs/statistics', [UrlLogController::class, 'statistic'])->name('url-logs.statistics');
    Route::resource('url-logs', UrlLogController::class);
    Route::resource('translation-texts', TranslationTextController::class);
    Route::resource('languages', LanguageController::class);
    Route::post('user-locale', [LanguageController::class, 'setUserLocale'])->name('user-locale');

});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('test', function () {

    return view('welcome');
});

require __DIR__ . '/auth.php';
