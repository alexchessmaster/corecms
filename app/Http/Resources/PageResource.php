<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Resources\PageWidgetResource;
use App\Http\Resources\WidgetableResource;
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
        foreach(Language::all() as $language){
            foreach($this->getTranslations('slug') as $lang => $slug){
                if($language->code === $lang){
                    $allUrls[$lang] = $language->domain . $slug;
                }
            }
        }

        // dd($this->widgets);
        
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
            // "template" => $this->blog,
            "primary_language" => $this->primary_language,
            // "widgets" => $this->relationLoaded('widgets') ? WidgetResource::collection($this->widgets) : null,
            // "widgetables" => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,
            // "page_widgets" => $this->relationLoaded('pageWidgets') ? PageWidgetResource::collection($this->pageWidgets) : null,

            'widgets' => $this->relationLoaded('widgetables') ? WidgetableResource::collection($this->widgetables->sortBy('position')) : null,

            // 'widgets' => $this->widgetables->sortBy('position')->map(function ($widgetable) {
            //     $widget = $widgetable->widget;
            //     $fieldValues = $widgetable->widgetFieldValues->keyBy('field_widget_id');

            //     return [
            //         'id' => $widget->id,
            //         'name' => $widget->name,
            //         'key' => $widget->key,
            //         'image' => $widget->image,
            //         'active' => $widget->active,
            //         'locked_fields_value' => $widget->locked_fields_value,
            //         'fields' => $widget->fieldWidgets->map(function ($fieldWidget) use ($fieldValues) {
            //             return [
            //                 'id' => $fieldWidget->field->id,
            //                 'key' => $fieldWidget->key,
            //                 'type' => $fieldWidget->field->type,
            //                 'value' => optional($fieldValues->get($fieldWidget->id))->value,
            //             ];
            //         }),
            //     ];
            // }),
        ];
    }
}
