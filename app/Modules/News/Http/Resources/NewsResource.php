<?php

namespace App\Modules\News\Http\Resources;

use App\Modules\Widgets\Http\Resources\WidgetableResource;
use App\Modules\News\Http\Resources\NewsAuthorResource;
use App\Modules\News\Http\Resources\NewsCategoryResource;
use App\Modules\News\Http\Resources\NewsTagResource;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Modules\Shared\Helpers\FileHelper;
use App\Modules\Shared\Helpers\TranslationHelper;
use App\Modules\Shared\Helpers\UrlHelper;
use App\Modules\Languages\Repositories\LanguageRepository;
use App\Modules\Settings\Repositories\SettingRepository;
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
        $settingRepository = app(SettingRepository::class);
        $newsPrefix = $settingRepository->findByKey(SettingKeyEnum::NEWS_PREFIX);
        $allUrls = [];
        $languageRepository = app(LanguageRepository::class);
        $languages = $languageRepository->all();
        foreach($languages as $language){
            foreach($this->getTranslations('slug') as $lang => $slug){
                if($language->code === $lang){
                    $allUrls[$lang] = UrlHelper::getFullUrlBySlug($slug, $this, null, $lang);
                }
            }
        }

        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "all_urls" => $allUrls,
            "full_url" => UrlHelper::getFullUrlBySlug($this->slug, $this, null, app()->getLocale(), true),
            "description" => $this->description,
            "news_date" => $this->news_date->format('Y-m-d'),
            "stars" => $this->stars,
            "content" => $this->content,
            "image" => FileHelper::addDomainPrefixIfValueIsAFile(
                TranslationHelper::firstAvailableValue($this, 'image')
            ),
            "image_medium" => FileHelper::addDomainPrefixIfValueIsAFile(
                TranslationHelper::firstAvailableValue($this, 'image_medium')
            ) ?: FileHelper::addDomainPrefixIfValueIsAFile(
                TranslationHelper::firstAvailableValue($this, 'image')
            ),
            "image_thumbnail" => FileHelper::addDomainPrefixIfValueIsAFile(
                TranslationHelper::firstAvailableValue($this, 'image_thumbnail')
            ) ?: FileHelper::addDomainPrefixIfValueIsAFile(
                TranslationHelper::firstAvailableValue($this, 'image')
            ),
            "news_category_id" => $this->news_category_id,
            "category" => $this->relationLoaded('category') ? new NewsCategoryResource($this->category) : '',
            "author_id" => $this->author_id,
            "author" => $this->relationLoaded('author') ? new NewsAuthorResource($this->author) : null,
            "tags" => $this->relationLoaded('tags') ? NewsTagResource::collection($this->tags) : [],
            "views" => $this->views,
            "total_pages" => $this->total_pages,
            "primary_language" => $this->primary_language,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
            'sitemap_exclude' => $this->sitemap_exclude,
            'prefix' => $newsPrefix,
        ];
    }
}
