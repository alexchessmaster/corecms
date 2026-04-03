<?php

namespace App\Modules\Widgets\Models;

use App\Modules\Pages\Models\Page;
use App\Modules\Widgets\Models\Field;
use App\Modules\Widgets\Models\Widgetable;
use App\Modules\Widgets\Models\FieldWidget;
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

        static::updating(function ($widget) {
            // todo: check if it's working
            $baseSlug = Str::slug($widget->name);
            $slug = $baseSlug;
            $counter = 2;
            while (Widget::where('key', $slug)->where('id', '!=', $widget->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $widget->key = $slug;
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
