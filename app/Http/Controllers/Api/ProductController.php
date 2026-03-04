<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\PageWidget;
use App\Http\Controllers\Controller;
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
