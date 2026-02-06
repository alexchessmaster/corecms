<?php

namespace App\Modules\News\Http\Resources;

use App\Modules\Shared\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsAuthorResource extends JsonResource
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
            'news_count' => $this->news_count,
            'date_of_birth' => $this->date_of_birth,
            'date_of_death' => $this->date_of_death,
            // 'news' => NewsResource::collection($this->whenLoaded('news'))
        ];
    }
}
