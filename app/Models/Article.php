<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Category;
use App\Models\Widgetable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Article extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'slug', 'content', 'description', 'image', 'image_medium', 'image_thumbnail'];
    protected $casts = [
        'scheduled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getFullUrlAttribute()
    {
        $fullUrl = $this->slug;

        $articlePrefix = cache()->remember('article-prefix', 3600, function () {
            return Setting::where('key', 'article-prefix')->value('value');
        });

        if (!empty($articlePrefix)) {
            $articlePrefix = '/' . trim($articlePrefix, '/');
            $fullUrl = $articlePrefix . $fullUrl;
        }

        $languages = Language::all();
        $multipleLanguages = cache()->remember('is-multiple-languages', 3600, function () use ($languages) {
            return count($languages) > 1;
        });

        if ($multipleLanguages) {
            $lang = app()->getLocale();
            if (! $languages->value('use_separate_domain')) {
                $fullUrl = '/' . $lang . $fullUrl;
            }
        }

        return $fullUrl;
    }

    public function scopeWithAllWidgetData($query)
    {
        return $query->with([
            'widgetables.widget.fieldWidgets.field',
            'widgetables.widgetFieldValues.fieldWidget.field',
        ]);
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->can('view articles')) {
            return $query;
        }

        if ($user->can('view own articles')) {
            return $query->where('user_id', $user->id);
        }
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
