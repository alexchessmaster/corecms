<?php

namespace App\Http\Resources;

use App\Models\Language;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Http\Resources\WidgetableResource;
use App\Http\Resources\ProductAuthorResource;
use App\Http\Resources\ProductCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $productPrefix = $this->additional['product_prefix'] ?? null;

        $allUrls = [];
        foreach(Language::all() as $language){
            foreach($this->getTranslations('slug') as $lang => $slug){
                if($language->code === $lang){
                    if($productPrefix) {
                        $allUrls[$lang] = $language->domain . $productPrefix . $slug;
                    }else{
                        $allUrls[$lang] = $language->domain . $slug;
                    }
                }
            }
        }
        
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "all_urls" => $allUrls,
            "full_url" => $this->full_url,
            "description" => $this->description,
            "stars" => $this->stars,
            "content" => $this->content,
            "image" => FileHelper::addDomainPrefixIfValueIsAFile($this->image),
            "product_category_id" => $this->product_category_id,
            "productCategory" => $this->relationLoaded('productCategory') ? new ProductCategoryResource($this->productCategory) : null,
            "published_year" => $this->published_year,
            "author_id" => $this->author_id,
            "author" => $this->relationLoaded('author') ? new ProductAuthorResource($this->author) : null,
            "views" => $this->views,
            "total_pages" => $this->total_pages,
            "primary_language" => $this->primary_language,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
        ];
    }
}
