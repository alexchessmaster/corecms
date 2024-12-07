<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WidgetResource extends JsonResource
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
            "page_id" => $this->page_id,
            "key" => $this->key,
            "name" => $this->name,
            "user_note" => $this->user_note,
            "image" => $this->image,
            "active" => $this->active,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "fields" => FieldResource::collection($this->fields),
        ];
    }
}
