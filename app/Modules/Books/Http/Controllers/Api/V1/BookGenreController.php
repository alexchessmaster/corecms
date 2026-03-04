<?php

namespace App\Modules\Books\Http\Controllers\Api\V1;

use App\Models\Page;
use App\Models\Article;
use App\Models\Category;
use App\Modules\Books\Models\BookGenre;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;
use App\Modules\Books\Http\Resources\BookGenreResource;

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
