<?php

namespace App\Modules\Pages\Http\Resources;

use App\Modules\Widgets\Http\Resources\WidgetableResource;
use App\Modules\Languages\Models\Language;
use App\Modules\Shared\Helpers\UrlHelper;
use App\Modules\Languages\Repositories\LanguageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allUrls = [];
        $languageRepository = app(LanguageRepository::class);
        $languages = $languageRepository->all();
        foreach ($languages as $language) {
            foreach ($this->getTranslations('slug') as $lang => $slug) {
                if ($language->code === $lang) {
                    $allUrls[$lang] = UrlHelper::getFullUrlBySlug($slug, $this, null, $lang);
                }
            }
        }

        return [
            "id" => $this->id,
            "slug" => $this->slug,
            "all_urls" => $allUrls,
            "title" => $this->title,
            "status" => $this->status,
            "published_at" => $this->published_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "sitemap_exclude" => $this->sitemap_exclude,
            "primary_language" => $this->primary_language,
            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
            'sitemap_exclude' => $this->sitemap_exclude,
        ];
    }
}
