<?php

namespace App\Modules\Articles\Http\Controllers\Api;

use App\Modules\Pages\Models\Page;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\Category;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Pages\Http\Resources\PageResource;
use App\Modules\Articles\Http\Resources\ArticleResource;
use App\Modules\Articles\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function show($categoryId)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $category = Category::withAllWidgetData()->find($categoryId);

        return response()->json(CategoryResource::make($category));
    }
}
