<?php

namespace App\Modules\News\Http\Resources;

use App\Models\Language;
use App\Modules\Shared\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Http\Resources\WidgetableResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\News\Http\Resources\NewsTagResource;
use App\Modules\News\Http\Resources\NewsAuthorResource;
use App\Modules\News\Http\Resources\NewsCategoryResource;

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
        foreach(Language::all() as $language){
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

        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "all_urls" => $allUrls,
            "full_url" => $this->full_url,
            "description" => $this->description,
            "stars" => $this->stars,
            "content" => $this->content,
            "image" => FileHelper::addDomainPrefixIfValueIsAFile($this->image),
            "news_category_id" => $this->news_category_id,
            "newsCategory" => $this->relationLoaded('category') ? new NewsCategoryResource($this->category) : 'yes',
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
