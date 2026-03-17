<?php

namespace App\Modules\Books\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\Books\Http\Resources\BookResource;
use App\Modules\Books\Models\Book;
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
}
