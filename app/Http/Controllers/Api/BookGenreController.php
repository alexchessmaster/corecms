<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\Article;
use App\Models\Category;
use App\Models\BookGenre;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\BookGenreResource;

class BookGenreController extends Controller
{
    public function show($bookGenreId)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $bookGenre = BookGenre::withAllWidgetData()->find($bookGenreId);

        return response()->json(BookGenreResource::make($bookGenre));
    }
}
