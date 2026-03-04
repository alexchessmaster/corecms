<?php

namespace App\Http\Controllers\Api;

use App\Models\PageWidget;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
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
