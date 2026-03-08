<?php

namespace App\Modules\News\Http\Resources;

use App\Http\Resources\WidgetableResource;
use App\Modules\News\Http\Resources\NewsAuthorResource;
use App\Modules\News\Http\Resources\NewsCategoryResource;
use App\Modules\News\Http\Resources\NewsTagResource;
use App\Modules\Shared\Helpers\FileHelper;
use App\Modules\Shared\Helpers\TranslationHelper;
use App\Repositories\LanguageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $newsPrefix = $this->additional['news_prefix'] ?? null;
        $allUrls = [];
        $languageRepository = new LanguageRepository;
        $languages = $languageRepository->all();
        foreach($languages as $language){
            foreach($this->getTranslations('slug') as $lang => $slug){
                if($language->code === $lang){
                    if($newsPrefix) {
                        $allUrls[$lang] = $language->domain . $newsPrefix . $slug;
                    }else{
                        $allUrls[$lang] = $language->domain . $slug;
                    }
                }
            }
        }

        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "all_urls" => $allUrls,
            "full_url" => $this->full_url,
            "description" => $this->description,
            "news_date" => $this->news_date->format('Y-m-d'),
            "stars" => $this->stars,
            "content" => $this->content,
            "image" => FileHelper::addDomainPrefixIfValueIsAFile(TranslationHelper::firstAvailableValue($this, 'image')),
            "news_category_id" => $this->news_category_id,
            "category" => $this->relationLoaded('category') ? new NewsCategoryResource($this->category) : '',
            "published_year" => $this->published_year,
            "author_id" => $this->author_id,
            "author" => $this->relationLoaded('author') ? new NewsAuthorResource($this->author) : null,
            "tags" => $this->relationLoaded('tags') ? NewsTagResource::collection($this->tags) : [],
            "views" => $this->views,
            "total_pages" => $this->total_pages,
            "primary_language" => $this->primary_language,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
        ];
    }
}
