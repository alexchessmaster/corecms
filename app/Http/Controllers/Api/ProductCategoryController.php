<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\Article;
use App\Models\Category;
use App\Models\BookGenre;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\BookGenreResource;
use App\Http\Resources\ProductCategoryResource;

class ProductCategoryController extends Controller
{
    public function show($productCategoryId)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $productCategory = ProductCategory::withAllWidgetData()->find($productCategoryId);

        return response()->json(ProductCategoryResource::make($productCategory));
    }
}
