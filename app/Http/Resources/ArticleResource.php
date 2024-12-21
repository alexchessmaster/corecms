<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Resources\CategoryResource;
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
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "content" => $this->content,
            "image" => $this->image,
            "category_id" => $this->category_id,
            "category" => $this->relationLoaded('category') ? new CategoryResource($this->category) : null,
            "tags" => $this->relationLoaded('tags') ? TagResource::collection($this->tags) : null,
            "template_page_id" => $this->template_page_id,
            "template_page" => $this->relationLoaded('page') ? new PageResource($this->page) : null,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
