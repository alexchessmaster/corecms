<?php


use App\Http\Controllers\CommentableController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SettingController;
use App\Modules\Articles\Http\Controllers\Admin\TagController;
use App\Http\Controllers\TranslationTextController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UrlLogController;
use App\Http\Controllers\WidgetController;
use App\Http\Middleware\LanguageAdminMiddleware;
use Illuminate\Support\Facades\Route;

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
    Route::resource('templates', PageController::class);
    Route::get('article-tags/select', [TagController::class, 'selectTags'])->name('article-tags.select-tags');
    Route::resource('comments', CommentableController::class);
    Route::resource('redirects', RedirectController::class);
    Route::get('url-logs/statistics', [UrlLogController::class, 'statistic'])->name('url-logs.statistics');
    Route::resource('url-logs', UrlLogController::class);
    Route::resource('translation-texts', TranslationTextController::class);
    Route::resource('languages', LanguageController::class);

});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('test', function () {
    app()->setLocale('fa');

    // $news = News::with('widgetables.widgetFieldValues')->get();
    // $news = [News::find(3)];
    // foreach ($news as $item) {
    //     foreach (Language::all() as $language) {
    //         $image = $item->getTranslation('image', $language->code, false);
    //         if (!empty($image)) {
    //             $imagePath = public_path($image);
    //             if (File::exists($imagePath)) {
    //                 $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
    //                 $newFilename = Str::uuid() . '.' . $extension;
    //                 $directory = dirname($imagePath);
    //                 $newPath = $directory . '/' . $newFilename;
    //                 File::move($imagePath, $newPath);
    //                 $folderName = basename($directory);
    //                 $item->setTranslation('image', $language->code, "/uploads/$folderName/" . $newFilename);
    //             }
    //         }
    //     }
    //     $item->saveQuietly();
    // }

    // dd($allNews[0]);

    // return StrHelper::removeUnicodeCharacters('-_');
    // foreach($allNews as $news) {
    //     $news = News::find(124);
    //     $title = $news->getTranslation('title', 'fa', false);
    //     if(!empty($title)){
    //         $news->setTranslation('title', 'fa', StrHelper::removeUnicodeCharacters($title));
    //     }
    //     // $slug = $news->getTranslation('slug', 'fa', false);
    //     // if(!empty($slug)){
    //     //     $news->setTranslation('slug', 'fa', str_replace(' ', '-', StrHelper::removeUnicodeCharacters($title)));
    //     // }
    //     $description = $news->getTranslation('description', 'fa', false);
    //     if(!empty($description)){
    //         $news->setTranslation('title', 'fa', StrHelper::removeUnicodeCharacters($title));
    //     }
    //     $news->saveQuietly();
    // }

    // foreach($allNews as $news) {
    //     // $news = News::find(123);
    //     $news->slug = '';
    //     $news->save();
    // }

    // $items = WidgetFieldValues::all();
    // foreach($items as $item){
    //     // $item = WidgetFieldValues::find(598);
    //     // {"fa": "<p>یک ناو جنگی ۱۰۴ متری روسیه به&zwnj;طور ناگهانی در بندرعباس پهلو گرفت و قرار است در رزمایش مشترک با نیروی دریایی ایران شرکت کند.<br>کارشناس دانمارکی این اقدام را غافلگیرکننده دانست و آن را تلاشی احتمالی برای جلوگیری از حمله آمریکا ارزیابی کرد</p>"}
    //     // {"fa": "<p>یک ناو جنگی ۱۰۴ متری روسیه به&zwnj;طور ناگهانی در بندرعباس پهلو گرفت و قرار است در رزمایش مشترک با نیروی دریایی ایران شرکت کند.<br>کارشناس دانمارکی این اقدام را غافلگیرکننده دانست و آن را تلاشی احتمالی برای جلوگیری از حمله آمریکا ارزیابی کرد</p>"}
    //     $value = $item->getTranslation('value', 'fa', false);
    //     $cleanValue = StrHelper::removeUnicodeCharacters($value);
    //     // dd($cleanValue);
    //     if($value !== $cleanValue){
    //         $item->setTranslation('value', 'fa', $cleanValue);
    //         $item->saveQuietly();
    //     }
    // }


    // News::query()->update(['sitemap_exclude' => true]);


    return 'done!';
});

require __DIR__ . '/auth.php';
