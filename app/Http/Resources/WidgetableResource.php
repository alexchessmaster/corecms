<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\WidgetResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\WidgetFieldValuesResource;

class WidgetableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $widget = $this->widget;

        $fieldValues = $this->widgetFieldValues->keyBy('field_widget_id');

        return [
            'id' => $this->id, // ID in the widgetables table
            'widgetable_id' => $this->widgetable_id, // page ID or other widgetable ID
            'widgetable_type' => $this->widgetable_type, // page or other widgetable type
            'widget_id' => $this->widget_id,
            'position' => $this->position,
            'image' => !empty($this->widget->image) ? (str_starts_with($this->widget->image, 'http') ? $this->widget->image : config('app.url') . $this->widget->image) : null,
            'locked_fields_value' => $this->widget->locked_fields_value,
            'name' => $this->widget->name,
            'key' => $this->widget->key,
            'fields' => $widget->fieldWidgets->map(function ($fieldWidget) use ($fieldValues) {
                $widgetFieldValue = $fieldValues->get($fieldWidget->id);
                $value = $widgetFieldValue?->value;

                return [
                    'id' => $fieldWidget->field->id,
                    'field_widget' => $fieldWidget->id,
                    'widget_field_value_id' => $widgetFieldValue?->id, // not used so far
                    'key' => $fieldWidget->key,
                    'type' => $fieldWidget->field->type,
                    'value' => !empty($value) && $fieldWidget->field->type === 'file' ? (str_starts_with($value, 'http') ? $value : config('app.url') . $value) : $value,
                ];
            }),
            
        ];
    }
}
