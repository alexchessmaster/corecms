<?php

namespace App\Modules\Products\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\Products\Http\Resources\ProductCategoryResource;
use App\Modules\Products\Models\ProductCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductCategoryController extends Controller
{
    use AuthorizesRequests;

    public function show($productCategoryId)
    {
        $productCategory = ProductCategory::withAllWidgetData()->find($productCategoryId);
        $this->authorize('view', $productCategory);
        if(!empty(request()->lang)){
            app()->setLocale(request()->lang);
        }

        return response()->json(ProductCategoryResource::make($productCategory));
    }
}
