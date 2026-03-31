<?php

namespace App\Modules\Products\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\Products\Http\Resources\ProductResource;
use App\Modules\Products\Models\Product;
use App\Modules\Shared\Jobs\GenerateSitemapsJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function show($productId)
    {
        if(!empty(request()->lang)){
            app()->setLocale(request()->lang);
        }
        $product = Product::withAllWidgetData()->find($productId);
        $this->authorize('view', $product);

        return response()->json(ProductResource::make($product));
    }

    public function removeProductLanguage($id, $lang)
    {
        $product = Product::findOrFail($id);
        $this->authorize('edit', $product);
        $titleTranslations = $product->getTranslations('title');
        if (count($titleTranslations) <= 1) {
            // It has only one language.
            $this->authorize('delete', $product);
            $product->delete();
            GenerateSitemapsJob::dispatch();

            return response()->json([
                'message' => "Product deleted successfully.",
                'status' => 'deleted',
            ]);
        }
        $titleTranslation = $product->getTranslation('title', $lang, false);
        $slugTranslation = $product->getTranslation('slug', $lang, false);
        $langExist = false;
        if (!empty($titleTranslation)) {
            $langExist = true;
            $product->forgetTranslation('title', $lang);
            $product->save();
        }
        if (!empty($slugTranslation)) {
            $langExist = true;
            $product->forgetTranslation('slug', $lang);
            $product->save();
        }
        if (! $langExist) {
            return response()->json([
                'message' => "Product doesn't have $lang language.",
                'status' => 'error',
            ], 404);
        }
        if (!empty($slugTranslation)) {
            $product->forgetTranslation('slug', $lang);
            $product->saveQuietly();
        }

        GenerateSitemapsJob::dispatch();

        return response()->json([
            'message' => "The $lang language removed successfully",
            'status' => 'success',
        ]);
    }
}
