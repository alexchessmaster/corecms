<?php

namespace App\Models;

use App\Models\Field;
// use App\Models\FieldValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FieldWidget extends Model
{
    /** @use HasFactory<\Database\Factories\FieldWidgetFactory> */
    use HasFactory;
    protected $table = 'field_widget';
    protected $guarded = [];
    public $timestamps = false;

    // public function values()
    // {
    //     return $this->hasMany(FieldValue::class, 'field_widget_id');
    // }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }
}
