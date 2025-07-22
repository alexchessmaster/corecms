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
        // dd($this);
        return [
            "id" => $this->id,
            // "field" => FieldResource::make($this->field),
            "type" => $this->type,
            // "value" => $this->value?->value,

        ];
    }
}
