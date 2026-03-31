<?php

namespace App\Modules\Books\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\Books\Http\Resources\BookResource;
use App\Modules\Books\Models\Book;
use App\Modules\Shared\Jobs\GenerateSitemapsJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookController extends Controller
{
    use AuthorizesRequests;
    
    public function show($bookId)
    {
        if(!empty(request()->lang)){
            app()->setLocale(request()->lang);
        }

        $book = Book::withAllWidgetData()->find($bookId);
        $this->authorize('view', $book);

        return response()->json(BookResource::make($book));
    }

    public function removeBookLanguage($id, $lang)
    {
        $book = Book::findOrFail($id);
        $this->authorize('edit', $book);
        $titleTranslations = $book->getTranslations('title');
        if (count($titleTranslations) <= 1) {
            // It has only one language.
            $this->authorize('delete', $book);
            $book->delete();
            GenerateSitemapsJob::dispatch();

            return response()->json([
                'message' => "Book deleted successfully.",
                'status' => 'deleted',
            ]);
        }
        $titleTranslation = $book->getTranslation('title', $lang, false);
        $slugTranslation = $book->getTranslation('slug', $lang, false);
        $langExist = false;
        if (!empty($titleTranslation)) {
            $langExist = true;
            $book->forgetTranslation('title', $lang);
            $book->save();
        }
        if (!empty($slugTranslation)) {
            $langExist = true;
            $book->forgetTranslation('slug', $lang);
            $book->save();
        }
        if (! $langExist) {
            return response()->json([
                'message' => "Book doesn't have $lang language.",
                'status' => 'error',
            ], 404);
        }
        if (!empty($slugTranslation)) {
            $book->forgetTranslation('slug', $lang);
            $book->saveQuietly();
        }

        GenerateSitemapsJob::dispatch();

        return response()->json([
            'message' => "language $lang removed successfully",
            'status' => 'success',
        ]);
    }
}
