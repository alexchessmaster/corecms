<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageWidgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $data = [
            "id" => $this->id,
            "page_id" => $this->page_id,
            "widget_id" => $this->widget_id,
            "position" => $this->position,
            "fieldValues" => FieldValueResource::collection($this->fieldValues),
        ];

        return $data;
    }
}
