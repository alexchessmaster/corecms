<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Page;
use App\Models\Article;
use App\Models\Product;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function show($productId)
    {
        if(!empty(request()->lang)){
            app()->setLocale(request()->lang);
        }

        $product = Product::withAllWidgetData()->find($productId);

        return response()->json(ProductResource::make($product));
    }
}
