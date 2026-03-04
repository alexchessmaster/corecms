<?php

namespace App\Modules\Products\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\Products\Http\Resources\ProductResource;
use App\Modules\Products\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function show($productId)
    {
        $product = Product::withAllWidgetData()->find($productId);
        $this->authorize('view', $product);
        if(!empty(request()->lang)){
            app()->setLocale(request()->lang);
        }

        return response()->json(ProductResource::make($product));
    }
}
