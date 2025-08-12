<?php

namespace App\Http\Resources;

use App\Helpers\FileHelper;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\BookGenreResource;
use App\Http\Resources\WidgetableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $bookPrefix = $this->additional['book_prefix'] ?? null;

        $allUrls = [];
        foreach(Language::all() as $language){
            foreach($this->getTranslations('slug') as $lang => $slug){
                if($language->code === $lang){
                    if($bookPrefix) {
                        $allUrls[$lang] = $language->domain . $bookPrefix . $slug;
                    }else{
                        $allUrls[$lang] = $language->domain . $slug;
                    }
                }
            }
        }
        
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "all_urls" => $allUrls,
            "full_url" => $this->full_url,
            "description" => $this->description,
            "content" => $this->content,
            "image" => FileHelper::addDomainPrefixIfValueIsAFile($this->image),
            "book_genre_id" => $this->book_genre_id,
            "bookGenre" => $this->relationLoaded('bookGenre') ? new BookGenreResource($this->bookGenre) : null,
            "primary_language" => $this->primary_language,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
        ];
    }
}
