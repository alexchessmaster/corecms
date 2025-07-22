<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\FieldWidgetResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WidgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->fieldValues);
        // return parent::toArray($request);
        info('$this->fieldWidgets');
        info($this->fieldWidgets);
        return [
            "id" => $this->id,
            // "page_id" => $this->page_id,
            "key" => $this->key,
            "name" => $this->name,
            "user_note" => $this->user_note,
            "image" => str_starts_with($this->image, 'http') ? $this->image : config('app.url') . $this->image,
            "locked_fields_value" => $this->locked_fields_value,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "fields" => $this->relationLoaded('fieldWidgets') ? FieldWidgetResource::collection($this->fieldWidgets) : null,
            // "field_values" => $this->relationLoaded('fieldValues') ? FieldValueResource::collection($this->fieldValues) : null,
        ];
    }
}



        // return [
        //     "id" => $this->id,
        //     // "page_id" => $this->page_id,
        //     "key" => $this->key,
        //     "name" => $this->name,
        //     // "user_note" => $this->pivot->user_note,
        //     "image" => $this->image ? (str_starts_with($this->image, 'http') ? $this->image : config('app.url') . $this->image) : null,
        //     "locked_fields_value" => $this->locked_fields_value,

        //     'fields' => $this->relationLoaded('fieldWidgets') ? FieldWidgetResource::collection($this->fieldWidgets) : null,
        //     // 'fields' => $this->relationLoaded('fieldWidgets') ? FieldResource::collection(
        //     //     $this->fieldWidgets->map(function ($fieldWidget) {
        //     //         // dd($fieldWidget); // field_widget->field
        //     //         dd($this); // widget->fieldWidgets
        //     //         $fieldWidget->value = $this->widgetFieldValues
        //     //             ->where('field_widget_id', $fieldWidget->id)
        //     //             ->first();
        //     //         return $fieldWidget;
        //     //     })
        //     // ) : null,
        //     // "fields" => $this->relationLoaded('fieldWidgets') ? FieldResource::collection($this->fields) : null,
        //     // "widget_field_values" => $this->relationLoaded('fieldValues') ? FieldValueResource::collection($this->fieldValues) : null,
        // ];
