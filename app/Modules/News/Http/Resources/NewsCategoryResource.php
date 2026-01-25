<?php

namespace App\Modules\News\Http\Resources;

use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Http\Resources\WidgetableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "slug" => $this->slug,
            "description" => $this->description,
            "parent_id" => $this->parent_id,
            "image" => FileHelper::addDomainPrefixIfValueIsAFile($this->image),
            "parent" => $this->relationLoaded('parent') ? NewsCategoryResource::make($this->parent) : null,
            "children" => $this->relationLoaded('children') ? NewsCategoryResource::collection($this->children) : null,
            "news" => $this->relationLoaded('news') ? NewsResource::collection($this->books) : [],
            "news_count" => $this->news_count ?? null, // check it
            "primary_language" => $this->primary_language,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
        ];
    }
}
