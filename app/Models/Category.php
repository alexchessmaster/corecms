<?php

namespace App\Models;

use App\Models\Article;
use App\Models\Widgetable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Category extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'slug', 'description', 'parent_id', 'sitemap_exclude', 'sitemap_priority', 'sitemap_change_frequency', 'primary_language'];
    public $translatable = ['name', 'slug', 'description'];
    protected $with = ['parent'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function scopeWithAllWidgetData($query)
    {
        return $this->with([
            'widgetables.widget.fieldWidgets.field',
            'widgetables.widgetFieldValues.fieldWidget.field',
        ]);
    }

    public function widgetables(): MorphMany
    {
        return $this->morphMany(Widgetable::class, 'widgetable')->orderBy('position');
    }

    public function widgets()
    {
        return $this->hasManyThrough(
            Widget::class,
            Widgetable::class,
            'widgetable_id',
            'id',
            'id',
            'widget_id'
        )->where('widgetables.widgetable_type', self::class);
    }
}
