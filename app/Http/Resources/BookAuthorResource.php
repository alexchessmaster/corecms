<?php

namespace App\Http\Resources;

use App\Modules\Shared\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookAuthorResource extends JsonResource
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
            'image' => FileHelper::addDomainPrefixIfValueIsAFile($this->image),
            'book_count' => $this->books_count,
            'date_of_birth' => $this->date_of_birth,
            'date_of_death' => $this->date_of_death,
            'books' => BookResource::collection($this->whenLoaded('books'))
        ];
    }
}
