<?php

use App\Modules\Books\Jobs\FillBookPageImageFolderJob;
use App\Modules\Books\Models\Book;
use App\Modules\Languages\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\Shared\Jobs\DeployFrontendJob;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => [LanguageAdminMiddleware::class, 'auth', 'verified']
], function () {
    //    Route::resource('templates', PageController::class); // check it later if it should be removed
});

Route::get('test', function () {
    $id = 24;
    $lang = 'fa';
    $path = '/uploads/68a5fac87afa0.pdf';

    app()->setLocale($lang);
    $book = Book::withAllWidgetData()->find($id);

    return $book;

    $book->setTranslation('pdf', $lang, $path);
    $book->save();

    return 'done';
});

Route::get('test2', function () {
    app()->setLocale('en');
    $books = Book::get();
    // foreach ($books as $book) {
    //     $job = FillBookPageImageFolderJob::dispatch($book);
    // }
    return $books;


    return response()->stream(function () use ($job) {
        if (function_exists('ob_implicit_flush')) {
            ob_implicit_flush(true);
        }
        echo "Starting FillBookPageImageFolderJob...\n";
        flush();

        $job->processWithOutput(function (string $message) {
            echo $message . "\n";
            flush();
        });

        echo "Finished FillBookPageImageFolderJob.\n";
        flush();
    }, 200, ['Content-Type' => 'text/plain; charset=utf-8', 'Cache-Control' => 'no-cache']);
});

require __DIR__ . '/auth.php';
