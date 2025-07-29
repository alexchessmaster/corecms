<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Page;
use App\Models\Article;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\ArticleResource;

class BookController extends Controller
{
    public function show($bookId)
    {
        $book = Book::withAllWidgetData()->find($bookId);

        return response()->json(BookResource::make($book));
    }
}
