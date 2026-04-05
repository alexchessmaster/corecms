<?php

namespace App\Modules\Articles\Http\Resources;

use App\Modules\Articles\Http\Resources\CategoryResource;
use App\Modules\Articles\Http\Resources\TagResource;
use App\Modules\Languages\Repositories\LanguageRepository;
use App\Modules\Settings\Repositories\SettingRepository;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Modules\Shared\Helpers\FileHelper;
use App\Modules\Shared\Helpers\TranslationHelper;
use App\Modules\Shared\Helpers\UrlHelper;
use App\Modules\Users\Http\Resources\AuthorResource;
use App\Modules\Widgets\Http\Resources\WidgetableResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $settingRepository = app(SettingRepository::class);
        $articlePrefix = $settingRepository->findByKey(SettingKeyEnum::ARTICLE_PREFIX);
        $allUrls = [];
        $languageRepository = app(LanguageRepository::class);
        $languages = $languageRepository->all();
        foreach ($languages as $language) {
            foreach ($this->getTranslations('slug') as $lang => $slug) {
                if($language->code === $lang){
                    $allUrls[$lang] = UrlHelper::getFullUrlBySlug($slug, $this, null, $lang);
                }
            }
        }

        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "all_urls" => $allUrls,
            "full_url" => UrlHelper::getFullUrlBySlug($this->slug, $this, null, app()->getLocale(), true),
            "description" => $this->description,
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
            "category_id" => $this->category_id,
            "category" => $this->relationLoaded('category') ? new CategoryResource($this->category) : null,
            "tags" => $this->relationLoaded('tags') ? TagResource::collection($this->tags) : null,
            "author_id" => $this->author_id,
            "author" => $this->relationLoaded('author') ? new AuthorResource($this->author) : null,
            // "views" => $this->views,
            "primary_language" => $this->primary_language,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at->format('Y-m-d'),
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
            'sitemap_exclude' => $this->sitemap_exclude,
            'prefix' => $articlePrefix,
        ];
    }
}
