<?php

namespace App\Http\Resources;

use App\Modules\Shared\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WidgetFieldValuesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this);
        return [
            'id' => $this->id,
            'field_widget_id' => $this->field_widget_id,
            'widgetable_id' => $this->widgetable_id,
            // Maybe it need TranslatorHelper in future for value:
            'value' => FileHelper::addDomainPrefixIfValueIsAFile($this->value),
            'key' => $this?->fieldWidget?->key,// for easy access to key
            'type' => $this?->fieldWidget?->field?->type, // for easy access to field type
            'fieldWidget' => $this->relationLoaded('fieldWidget') ? new FieldWidgetResource($this->fieldWidget) : null,
        ];
    }
}
