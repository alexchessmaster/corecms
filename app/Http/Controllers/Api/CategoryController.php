<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\Article;
use App\Models\Category;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function show($categoryId)
    {
        $category = Category::withAllWidgetData()->find($categoryId);

        return response()->json(CategoryResource::make($category));
    }
}
