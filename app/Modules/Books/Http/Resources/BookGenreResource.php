<?php

namespace App\Modules\Books\Http\Resources;

use App\Modules\Shared\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Modules\Books\Http\Resources\BookResource;
use App\Http\Resources\WidgetableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookGenreResource extends JsonResource
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
            "name" => $this->name,
            "slug" => $this->slug,
            "description" => $this->description,
            "parent_id" => $this->parent_id,
            "image" => FileHelper::addDomainPrefixIfValueIsAFile($this->image),
            "parent" => $this->relationLoaded('parent') ? BookGenreResource::make($this->parent) : null,
            "children" => $this->relationLoaded('children') ? BookGenreResource::collection($this->children) : null,
            "books" => $this->relationLoaded('books') ? BookResource::collection($this->books) : null,
            "books_count" => $this->books_count ?? null,
            "primary_language" => $this->primary_language,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
        ];
    }
}
