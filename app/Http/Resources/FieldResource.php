<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
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
            "widget_id" => $this->widget_id,
            "key" => $this->key,
            "value" => $this->value,
            "type" => $this->type,
            "order" => $this->order,
            "user_note" => $this->user_note,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
