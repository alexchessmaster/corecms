<?php

namespace App\Modules\Articles\Http\Resources;

use Illuminate\Http\Request;
use App\Modules\Articles\Http\Resources\ArticleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
            "articles" => $this->relationLoaded('articles') ? ArticleResource::collection($this->articles) : null,
            "template_page_id" => $this->template_page_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
