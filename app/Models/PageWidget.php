<?php

namespace App\Models;

use App\Models\Widget;
use App\Models\FieldValue;
use Illuminate\Database\Eloquent\Model;

class PageWidget extends Model
{
    protected $guarded = [];

    protected $table = 'page_widget';
    /**
     * Get the page associated with this pivot.
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the widget associated with this pivot.
     */
    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }

    /**
     * Get the field values associated with this pivot.
     */
    public function fieldValues()
    {
        return $this->hasMany(FieldValue::class, 'page_widget_id');
    }
}
