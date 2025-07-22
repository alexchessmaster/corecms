<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\WidgetableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $articlePrefix = $this->additional['article_prefix'] ?? null;

        $allUrls = [];
        foreach(Language::all() as $language){
            foreach($this->getTranslations('slug') as $lang => $slug){
                if($language->code === $lang){
                    if($articlePrefix) {
                        $allUrls[$lang] = $language->domain . $articlePrefix . $slug;
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
            "title_seo" => $this->title_seo,
            "slug" => $this->slug,
            "all_urls" => $allUrls,
            "full_url" => $this->full_url,
            "description" => $this->description,
            "description_seo" => $this->description_seo,
            "content" => $this->content,
            "image" => str_starts_with($this->image, 'http') ? $this->image : config('app.url') . $this->image,
            "category_id" => $this->category_id,
            "category" => $this->relationLoaded('category') ? new CategoryResource($this->category) : null,
            "tags" => $this->relationLoaded('tags') ? TagResource::collection($this->tags) : null,
            "template_page_id" => $this->template_page_id,
            "template_page" => $this->relationLoaded('page') ? new PageResource($this->page) : null,
            "primary_language" => $this->primary_language,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
        ];
    }
}
