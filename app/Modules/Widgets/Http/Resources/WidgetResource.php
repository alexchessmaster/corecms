<?php

namespace App\Modules\Widgets\Http\Resources;

use Illuminate\Http\Request;
use App\Modules\Widgets\Http\Resources\FieldWidgetResource;
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
        return [
            "id" => $this->id,
            "key" => $this->key,
            "name" => $this->name,
            "user_note" => $this->user_note,
            "image" => str_starts_with($this->image, 'http') ? $this->image : config('app.url') . $this->image,
            "locked_fields_value" => $this->locked_fields_value,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "fields" => $this->relationLoaded('fieldWidgets') ? FieldWidgetResource::collection($this->fieldWidgets) : null,
        ];
    }
}
