<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\WidgetController;
use App\Http\Middleware\LanguageApiMiddleware;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\V1\ContentController as ContentControllerV1;
use App\Http\Controllers\Api\ProductController;
use App\Http\Middleware\BookingAdminMiddleware;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Middleware\LogVisitedUrlMiddleware;
use App\Http\Controllers\Api\BookGenreController;
use App\Http\Controllers\Api\BookAuthorController;
use App\Http\Controllers\Api\CommonDataController;
use App\Http\Controllers\Api\WidgetableController;
use App\Http\Controllers\Api\FieldWidgetController;
use App\Http\Controllers\Api\FormContactUsController;
use App\Http\Middleware\CacheControlHeaderMiddleware;
use App\Http\Controllers\Api\FormNewsletterController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\BookingSlotTemplateController;
use App\Http\Controllers\Api\WidgetFieldValuesController;
use App\Http\Controllers\Api\BookingAppointmentController;
use App\Http\Controllers\Api\BookingReservationController;
use App\Http\Controllers\Api\BookingAvailabilityController;
use App\Http\Controllers\NordicStandard\Api\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Used in the frontend
Route::middleware([
    // 'throttle:2000,1'
])->group(function () {
    Route::get('/fetch-menu', [ContentController::class, 'fetchMenu']);
    Route::get('/fetch-languages', [ContentController::class, 'fetchLanguages']);
    Route::get('/fetch-settings', [ContentController::class, 'fetchSettings']);
    Route::get('/fetch-translations', [ContentController::class, 'fetchTranslations']);
    Route::get('/fetch-content', [ContentController::class, 'fetchContent'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-books', [ContentController::class, 'fetchBooks'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-book-genres', [ContentController::class, 'fetchBookGenres']);
    Route::get('/fetch-authors', [ContentController::class, 'fetchAuthors']);
    Route::get('/fetch-book-comments', [ContentController::class, 'fetchBookComments']);
    // Route::get('/fetch-articles', [ContentController::class, 'fetchArticles']); // later
    // Route::get('/fetch-categories', [ContentController::class, 'fetchCategories']); // later
});

// API v1
Route::middleware([
    // 'throttle:2000,1'
])->prefix('/v1')->name('.v1')->group(function () {
    Route::get('/fetch-menu', [ContentControllerV1::class, 'fetchMenu']);
    Route::get('/fetch-languages', [ContentControllerV1::class, 'fetchLanguages']);
    Route::get('/fetch-settings', [ContentControllerV1::class, 'fetchSettings']);
    Route::get('/fetch-translations', [ContentControllerV1::class, 'fetchTranslations']);
    Route::get('/fetch-content', [ContentControllerV1::class, 'fetchContent'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-books', [ContentControllerV1::class, 'fetchBooks'])->middleware([LogVisitedUrlMiddleware::class, CacheControlHeaderMiddleware::class]);
    Route::get('/fetch-book-genres', [ContentControllerV1::class, 'fetchBookGenres']);
    Route::get('/fetch-authors', [ContentControllerV1::class, 'fetchAuthors']);
    Route::get('/fetch-book-comments', [ContentControllerV1::class, 'fetchBookComments']);
    // Route::get('/fetch-articles', [ContentController::class, 'fetchArticles']); // later
    // Route::get('/fetch-categories', [ContentController::class, 'fetchCategories']); // later
});

Route::post('/store-book-comments', [ContentController::class, 'storeBookComments'])->middleware('throttle:1,1');

// Used in admin-panel
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/pages', PageController::class);
    Route::apiResource('/articles', ArticleController::class);
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/books', BookController::class);
    Route::apiResource('/bookgenres', BookGenreController::class);
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/productcategories', ProductCategoryController::class);
    Route::patch('/widget-field-values', [WidgetFieldValuesController::class, 'update']);
    Route::patch('/widgets/attach', [WidgetController::class, 'attach']);
    Route::patch('/widgets/detach', [WidgetController::class, 'detach']);
    Route::get('/widgets/{id}', [WidgetController::class, 'show']);
    Route::apiResource('/widgets/{widget_id}/fields', FieldWidgetController::class);
    Route::get('/book-authors', [BookAuthorController::class, 'index']);
});

// custom routes:
Route::prefix('contact-us')->middleware([LanguageApiMiddleware::class, 'throttle:10,1'])->group(function () {
    // Contact us - Public
    Route::post('', [FormContactUsController::class, 'store']);
    // Admin only
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('', [FormContactUsController::class, 'index']);
        Route::get('/statistics', [FormContactUsController::class, 'statistics']);
        Route::get('/{uuid}', [FormContactUsController::class, 'show']);
        Route::patch('/{uuid}/status', [FormContactUsController::class, 'updateStatus']);
        Route::delete('/{uuid}', [FormContactUsController::class, 'destroy']);
    });
});
Route::prefix('newsletter')->middleware([LanguageApiMiddleware::class, 'throttle:10,1'])->group(function () {
    // Newsletter - Public
    Route::post('/subscribe', [FormNewsletterController::class, 'store']);
    Route::get('/verify/{token}', [FormNewsletterController::class, 'verify']);
    Route::post('/unsubscribe/{email}', [FormNewsletterController::class, 'unsubscribe']);
    Route::get('/status/{email}', [FormNewsletterController::class, 'status']);
});


// Booking System Routes
Route::prefix('booking')->middleware([LanguageApiMiddleware::class, 'throttle:100,1'])->group(function () {
    // Admin routes (require authentication)
    Route::middleware(['auth:sanctum', BookingAdminMiddleware::class])->group(function () {
        Route::get('/reservations', [BookingReservationController::class, 'index']);
        Route::get('/reservations/today', [BookingReservationController::class, 'today']);
        Route::get('/reservations/week', [BookingReservationController::class, 'week']);
        Route::get('/reservations/month', [BookingReservationController::class, 'month']);

        // Slot Template management (CRUD + toggle)
        Route::apiResource('/templates', BookingSlotTemplateController::class);
        Route::post('/templates/{id}/toggle', [BookingSlotTemplateController::class, 'toggleActive']);
    });

    // Public booking routes (no auth required)
    Route::get('/availability', [BookingAvailabilityController::class, 'checkAvailability']); // GET /api/booking/availability?date=YYYY-MM-DD
    Route::get('/appointments', [BookingAppointmentController::class, 'index']); // GET /api/booking/appointments?email=
    Route::post('/reservations', [BookingReservationController::class, 'bookAppointment']); // POST /api/booking/reservations
    // Future endpoints (not yet implemented in controller):
    // Route::patch('/appointments/{id}/cancel', [BookingAppointmentController::class, 'cancel']);
});
