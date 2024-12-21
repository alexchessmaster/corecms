<?php

use App\Models\Page;
use App\Models\Widget;
use App\Models\PageWidget;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\WidgetResource;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Middleware\LanguageAdminMiddleware;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified']);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => [AdminMiddleware::class, LanguageAdminMiddleware::class]], function () {
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
    Route::resource('tags', TagController::class);

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
    // $page = Page::find(1);

    $pageId = 1;
    // $widgetId = 5;
    $position = 1;

    $page = Page::find($pageId);
    dd($page->pageWidgets[0]->fieldValues);
    $widget = $page->widgets[0];//Widget::find(5); //
    dd($widget->fieldValues);

    // $pageWidget = PageWidget::where('page_id', $pageId)->where('widget_id', $widgetId)->where('position', $position)->first();
    // $pageWidget = PageWidget::where('page_id', $pageId)->where('position', $position)->first();

    // // dd($pageWidget);
    // dd($pageWidget->fieldValues);

    $pageWidget = PageWidget::where('page_id', $pageId)->where('position', $position)->first();

    $fieldValues = $pageWidget->fieldValues;

    dd($fieldValues);

    $widgetId = $pageWidget->widget_id;

    // $widget = Widget::find($widgetId);


    // $pageWidget = PageWidget::where('page_id', $pageId)->where('position', $position)->first();

    // $fieldValues = $pageWidget->fieldValues;

    // $widgetId = $pageWidget->widget_id;

    $widget = Widget::with('fields.values')->find($widgetId);

    // dd($widget->fields[0]);
    // $widget = Widget::find($widgetId);

    // dd(new WidgetResource($widget));
    // $field = $widget->fields[0];

    $widget = new WidgetResource($widget);

    return view('welcome', compact('widget'));
});

require __DIR__ . '/auth.php';
