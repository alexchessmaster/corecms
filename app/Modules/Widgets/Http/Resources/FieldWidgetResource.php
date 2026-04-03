<?php

namespace App\Modules\Widgets\Http\Resources;

use Illuminate\Http\Request;
use App\Modules\Widgets\Http\Resources\FieldResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldWidgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this);
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "key" => $this->key,
            "widget_id" => $this->widget_id,
            "field_id" => $this->field_id,
            'field_type' => $this?->field?->type, // for easy access to field_type
            // // "key" => $this->pivot->key, // Accessing the pivot table key
            // "field" => $this->relationLoaded('field') ? new FieldResource($this->field) : null,
        ];
    }
}
