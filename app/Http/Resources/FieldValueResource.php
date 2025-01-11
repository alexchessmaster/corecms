<?php

namespace App\Http\Resources;

use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldValueResource extends JsonResource
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
            'id' => $this->id,
            'field_id' => $this->field_id,
            'page_widget_id' => $this->page_widget_id,
            'value' => FileHelper::addDomainPrefixIfValueIsAFile($this->value),
            'field' => $this->relationLoaded('field') ? new FieldResource($this->field) : null,
            'key' => $this->relationLoaded('field') ? $this->field->key : $this->id,
        ];
    }
}
