<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\PageWidgetResource;
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
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "slug" => $this->slug,
            "title" => $this->title,
            "template" => $this->blog,
            "widgets" => $this->relationLoaded('widgets') ? WidgetResource::collection($this->widgets) : null,
            "pageWidgets" => $this->relationLoaded('pageWidgets') ? PageWidgetResource::collection($this->pageWidgets) : null,

            // "widget and fields"
        ];
    }
}
