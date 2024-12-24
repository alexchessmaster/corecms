<?php

namespace App\Models;

use App\Models\Widget;
use App\Models\FieldValue;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Field extends Model
{
    use HasTranslations;

    protected $translatable = ['value'];
    protected $guarded = [];

    public static function getTypes()
    {
        return ['input', 'textarea_one_line', 'textarea_small', 'textarea_large', 'file', 'color', 'code', 'select_option_left_center_right', 'select_option_on_off'];
    }

    public function widgets()
    {
        return $this->belongsTo(Widget::class);
    }

    /**
     * Get the values for this field.
     */
    public function values()
    {
        return $this->hasMany(FieldValue::class);
    }
}
