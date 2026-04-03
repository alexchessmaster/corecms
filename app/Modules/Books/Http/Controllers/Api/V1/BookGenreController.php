<?php

namespace App\Modules\Books\Http\Controllers\Api\V1;

use App\Modules\Pages\Models\Page;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\Category;
use App\Modules\Books\Models\BookGenre;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Pages\Http\Resources\PageResource;
use App\Modules\Articles\Http\Resources\ArticleResource;
use App\Modules\Articles\Http\Resources\CategoryResource;
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
