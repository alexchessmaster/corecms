<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\WidgetController;
use App\Http\Controllers\PageWidgetController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Middleware\LogVisitedUrlMiddleware;
use App\Http\Controllers\Api\CommonDataController;
use App\Http\Controllers\Api\WidgetableController;
use App\Http\Controllers\Api\FieldWidgetController;
use App\Http\Middleware\CacheControlHeaderMiddleware;
use App\Http\Controllers\Api\WidgetFieldValuesController;
use App\Http\Controllers\NordicStandard\Api\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/fetch-content', [ContentController::class, 'fetchContent'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
Route::get('/fetch-articles', [ContentController::class, 'fetchArticles']);
Route::get('/fetch-categories', [ContentController::class, 'fetchCategories']);
// Route::get('/common-data', [CommonDataController::class, 'index']);

Route::apiResource('/pages', PageController::class); // new
Route::apiResource('/articles', ArticleController::class); // new
Route::apiResource('/categories', CategoryController::class); // new
// Route::get('/pages/{page}/widget-position/{widgets_position}/field-values/{lang?}', [PageWidgetController::class, 'fieldValue']); // maybe old maybe new
// Route::get('/page/{pageId}/widget/{widgetId}/widget-position/{position}/fields-with-values/{lang?}', [WidgetController::class, 'getWidgetFieldsWithValues']); // I don't know where I used
// Route::patch('/pages/widget-position/update-field-value', [PageWidgetController::class, 'updateFieldValue']);
Route::patch('/widget-field-values', [WidgetFieldValuesController::class, 'update']); // new
Route::patch('/widgets/attach', [WidgetController::class, 'attach']); // new
Route::patch('/widgets/detach', [WidgetController::class, 'detach']); // new
Route::get('/widgets/{id}', [WidgetController::class, 'show']);
// Route::apiResource('/fields', FieldController::class); // should be deleted
Route::apiResource('/widgets/{widget_id}/fields', FieldWidgetController::class);

// NordicStandard.net custom routes:
Route::post('contact-us', [ContactController::class, 'submitContactForm']);

Route::get('widgets', function(){
    return view('text');
});
Route::get('match-pare', function(){
    return view('matchPare');
});
Route::get('fill-in-the-blank', function(){
    return view('fillInTheBlank');
});
Route::get('true-or-false', function(){
    return view('trueOrFalse');
});
Route::get('crossword', function(){
    return view('crossword');
});
Route::get('sorting', function(){
    return view('sorting');
});
Route::get('word-swipe', function(){
    return view('wordSwipe');
});

Route::post('ai', function(){
    // Http::post('http://poolai-backend.nordicstandard.net')
    $url = request()->url;
    $btn = request()->btn;
    if (empty($url) || empty($btn)) {
        return response()->json([
            'status' => 'error',
            'data' => 'Invalid params Btn: ' . $btn . ' or Url: ' . $url,
        ]);
    }
    $res = Http::post('http://poolai-backend.nordicstandard.net/api/handle-widget-ai', [
        'url' => $url,
        'btn' => $btn,
    ]);
    $bodyStr = $res->body();
    info('url: btn: '. $url . $btn);
    // info('hiiii res '. json_encode($bodyStr));
    
    $body = json_decode($bodyStr);
    info('hiiii res '. json_encode('bodyyyyyyy=' . json_encode($body)));
    return response()->json([
        'status' => 'success',
        'data' => $body->response->choices[0]->message->content,
    ]);
});
