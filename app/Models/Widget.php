<?php

namespace App\Models;

use App\Models\Page;
use App\Models\Field;
use App\Models\FieldValue;
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

    public function fields()
    {
        return $this->hasMany(Field::class);//??? // TODO: belongsToMany ?
    }

    public function fieldValues()
    {
        return $this->hasMany(FieldValue::class, 'page_widget_id');
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class);
    }
}
