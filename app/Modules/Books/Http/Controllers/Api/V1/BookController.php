<?php

namespace App\Modules\Books\Http\Controllers\Api\V1;

use App\Modules\Books\Models\Book;
use App\Models\Page;
use App\Models\Article;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Books\Http\Resources\BookResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\ArticleResource;

class BookController extends Controller
{
    public function show($bookId)
    {
        if(!empty(request()->lang)){
            app()->setLocale(request()->lang);
        }

        // TODO: get the token and check the permission

        $book = Book::withAllWidgetData()->find($bookId);

        return response()->json(BookResource::make($book));
    }
}
