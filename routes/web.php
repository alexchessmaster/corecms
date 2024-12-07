<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Middleware\LanguageAdminMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin', function(){
    return view('admin.dashboard');
})->middleware(['auth', 'verified']);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => [/*AdminMiddleware::class,*/ LanguageAdminMiddleware::class]], function(){
    Route::resource('menus', MenuController::class);
    Route::resource('upload', UploadController::class);
    Route::resource('settings', SettingController::class);
    Route::resource('pages', PageController::class);
    Route::resource('widgets', WidgetController::class);
    Route::post('widgets/sort', [WidgetController::class, 'sort'])->name('widgets.sort');
    Route::resource('fields', FieldController::class);
    Route::resource('users', UserController::class);

    Route::post('user-locale', function(){
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

require __DIR__.'/auth.php';
