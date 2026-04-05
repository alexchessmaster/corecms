<?php

namespace App\Modules\Articles\Http\Resources;

use App\Modules\Widgets\Http\Resources\WidgetableResource;
use Illuminate\Http\Request;
use App\Modules\Articles\Http\Resources\ArticleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            "parent" => $this->relationLoaded('parent') ? CategoryResource::make($this->parent) : null,
            "children" => $this->relationLoaded('children') ? CategoryResource::collection($this->children) : null,
            "articles" => $this->relationLoaded('articles') ? ArticleResource::collection($this->articles) : null,
            "template_page_id" => $this->template_page_id,
            "primary_language" => $this->primary_language,
            "hide_from_frontend" => $this->hide_from_frontend,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
        ];
    }
}
