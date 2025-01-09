<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            "link" => $this->link,
            "image" => config('app.url') . $this->image,
            "image_alt" => $this->image_alt,
            "description" => $this->description,
            "parent_id" => $this->parent_id,
            "children" => $this->relationLoaded('children') ? MenuResource::collection($this->children) : null,
            "order" => $this->order,
            "is_active" => $this->is_active,
        ];
    }
}
