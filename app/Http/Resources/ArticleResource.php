<?php

namespace App\Http\Resources;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\WidgetableResource;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Modules\Shared\Helpers\FileHelper;
use App\Modules\Shared\Helpers\TranslationHelper;
use App\Modules\Shared\Helpers\UrlHelper;
use App\Repositories\LanguageRepository;
use App\Stores\SettingStore;
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
        $settingStore = new SettingStore;
        $articlePrefix = $settingStore->findByKey(SettingKeyEnum::ARTICLE_PREFIX);
        $allUrls = [];
        $languageRepository = new LanguageRepository;
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
            "full_url" => $this->full_url,
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
            "primary_language" => $this->primary_language,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
            'prefix' => $articlePrefix,
        ];
    }
}
