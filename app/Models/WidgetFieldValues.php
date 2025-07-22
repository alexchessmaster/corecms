<?php

namespace App\Models;

use App\Models\Widgetable;
use App\Models\FieldWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class WidgetFieldValues extends Model
{
    /** @use HasFactory<\Database\Factories\WidgetFieldValuesFactory> */
    use HasFactory;
    use HasTranslations;

    protected $translatable = ['value'];
    protected $guarded = [];
    public $timestamps = false;

    public function widgetable()
    {
        return $this->belongsTo(Widgetable::class, 'widgetable_id');
    }

    public function fieldWidget()
    {
        return $this->belongsTo(FieldWidget::class, 'field_widget_id');
    }
}
