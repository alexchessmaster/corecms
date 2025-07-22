<?php

namespace App\Models;

use App\Models\Page;
use App\Models\Field;
use App\Models\Widgetable;
use App\Models\FieldWidget;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $guarded = [];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function($widget){
            if(empty($widget->key)){
                // TODO: we need slug-2 slug-3 and ... if duplicated
                $widget->key = Str::slug($widget->name);
            }
        });
    }

    // public function fields()
    // {
    //     return $this->belongsToMany(Field::class)->withPivot('key');
    // }

    // public function fieldsWithValues()
    // {
    //     return $this->belongsToMany(FieldValue::class, 'widget_field_value')
    //         ->withPivot('key');
    // }

    // public function pages()
    // {
    //     return $this->belongsToMany(Page::class);
    // }

    public function widgetables()
    {
        return $this->hasMany(Widgetable::class);
    }

    public function fieldWidgets()
    {
        return $this->hasMany(FieldWidget::class);
    }
}
