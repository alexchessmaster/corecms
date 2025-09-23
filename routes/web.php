<?php

use App\Models\Page;
use App\Models\Widget;
use App\Models\PageWidget;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\WidgetResource;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UrlLogController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\BookGenreController;
use App\Http\Controllers\BookAuthorController;
use App\Http\Controllers\CommentableController;
use App\Http\Controllers\ProductAuthorController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTagController;
use App\Http\Middleware\LanguageAdminMiddleware;
use App\Http\Controllers\TranslationTextController;
use App\Http\Controllers\AiPersonaController;
use App\Http\Controllers\AiChatController;

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
    Route::resource('book_genres', BookGenreController::class);
    Route::resource('book-authors', BookAuthorController::class);
    Route::resource('books', BookController::class);
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('product-tags', ProductTagController::class);
    Route::resource('product-authors', ProductAuthorController::class);
    Route::resource('products', ProductController::class);
    Route::resource('comments', CommentableController::class);
    Route::resource('tags', TagController::class);
    Route::resource('redirects', RedirectController::class);
    Route::get('url-logs/statistics', [UrlLogController::class, 'statistic'])->name('url-logs.statistics');
    Route::resource('url-logs', UrlLogController::class);
    Route::resource('translation-texts', TranslationTextController::class);
    
    // AI Personas management routes
    Route::resource('ai-personas', AiPersonaController::class);
    Route::get('my-personas', [AiPersonaController::class, 'myPersonas'])->name('ai-personas.my');
    Route::post('ai-personas/{aiPersona}/toggle', [AiPersonaController::class, 'toggleActive'])->name('ai-personas.toggle');
    Route::post('ai-personas/{aiPersona}/clone', [AiPersonaController::class, 'duplicate'])->name('ai-personas.clone');
    Route::get('popular-personas', [AiPersonaController::class, 'popular'])->name('ai-personas.popular');
    Route::get('search-personas', [AiPersonaController::class, 'search'])->name('ai-personas.search');

    // AI Chat session routes
    Route::resource('ai-chats', AiChatController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::get('ai-chats/{chat}/messages', [AiChatController::class, 'retrieveMessages'])->name('ai-chats.messages');
    Route::post('ai-chats/{chat}/send-message', [AiChatController::class, 'dispatchMessage'])->name('ai-chats.send');
    Route::delete('ai-chats/{chat}/clear', [AiChatController::class, 'purgeChat'])->name('ai-chats.clear');
    Route::get('ai-chats/{chat}/export', [AiChatController::class, 'downloadChat'])->name('ai-chats.export');
    Route::put('ai-chats/{chat}/change-persona', [AiChatController::class, 'switchPersona'])->name('ai-chats.change-persona');
    
    Route::post('user-locale', function () {
        session(['lang' => request()->lang]);
        App::setLocale(request()->lang);

        return redirect()->back();
    })->name('user-locale');
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
