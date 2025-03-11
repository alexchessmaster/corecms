<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\PageWidgetResource;
use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allUrls = [];
        foreach(Language::all() as $language){
            foreach($this->getTranslations('slug') as $lang => $slug){
                if($language->code === $lang){
                    $allUrls[$lang] = $language->domain . $slug;
                }
            }
        }
        
        return [
            "id" => $this->id,
            "slug" => $this->slug,
            "all_urls" => $allUrls,
            "title" => $this->title,
            "template" => $this->blog,
            "primary_language" => $this->primary_language,
            "widgets" => $this->relationLoaded('widgets') ? WidgetResource::collection($this->widgets) : null,
            "page_widgets" => $this->relationLoaded('pageWidgets') ? PageWidgetResource::collection($this->pageWidgets) : null,
        ];
    }
}
