<?php

namespace App\Models;

use App\Models\Widget;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FieldValue extends Model
{
    use HasTranslations;

    protected $translatable = ['value'];

    protected $guarded = [];

    /**
     * Get the field associated with the value.
     */
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Get the page-widget relationship associated with this value.
     */
    public function pageWidget()
    {
        return $this->belongsTo(PageWidget::class, 'page_widget_id');
    }

    // public function widget()
    // {
    //     return $this->belongsTo(Widget::class, 'page_widget_id');
    // }
}
