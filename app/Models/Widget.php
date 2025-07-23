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

        static::creating(function ($widget) {
            // todo: check if it's working
            if (empty($widget->key)) {
                $baseSlug = Str::slug($widget->name);
                $slug = $baseSlug;
                $counter = 2;
                while (Widget::where('key', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $widget->key = $slug;
            }
        });
    }

    public function widgetables()
    {
        return $this->hasMany(Widgetable::class);
    }

    public function fieldWidgets()
    {
        return $this->hasMany(FieldWidget::class);
    }
}
