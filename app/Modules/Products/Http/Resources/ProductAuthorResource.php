<?php

namespace App\Modules\Products\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', app()->getLocale()),
            'nationality' => $this->getTranslation('nationality', app()->getLocale()),
            'biography' => $this->getTranslation('biography', app()->getLocale()),
            'date_of_birth' => $this->date_of_birth,
            'date_of_death' => $this->date_of_death,
            'image' => $this->image,
        ];
    }
}
