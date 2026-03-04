<?php

namespace App\Modules\Products\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
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
            'slug' => $this->getTranslation('slug', app()->getLocale()),
            'description' => $this->getTranslation('description', app()->getLocale()),
            'image' => $this->getTranslation('image', app()->getLocale()),
            'parent_id' => $this->parent_id,
            'hide_from_frontend' => $this->hide_from_frontend,
            'status' => $this->status,
            'primary_language' => $this->primary_language,
        ];
    }
}
