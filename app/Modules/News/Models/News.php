<?php

namespace App\Modules\News\Models;

use App\Models\User;
use App\Models\Widget;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Widgetable;
use App\Modules\News\Models\NewsAuthor;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Modules\News\Models\NewsCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'slug', 'description', 'image'];
    protected $casts = [
        'scheduled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getFullUrlAttribute()
    {
        $fullUrl = $this->slug;

        $newsPrefix = cache()->remember('news-prefix', 3600, function () {
            return Setting::where('key', 'news-prefix')->value('value');
        });

        if (!empty($newsPrefix)) {
            $newsPrefix = '/' . trim($newsPrefix, '/');
            $fullUrl = $newsPrefix . $fullUrl;
        }

        $languages = Language::all();
        $multipleLanguages = cache()->remember('is-multiple-languages', 3600, function () use ($languages) {
            return count($languages) > 1;
        });

        if ($multipleLanguages) {
            $currentLocale = app()->getLocale();
            $fullUrl = '/' . $currentLocale . $fullUrl;
        }

        return $fullUrl;
    }

    public function scopeWithAllWidgetData($query)
    {
        return $query->with([
            'author',
            'widgetables.widget.fieldWidgets.field',
            'widgetables.widgetFieldValues.fieldWidget.field',
        ]);
    }

    public function scopeVisibleTo($query, User $user): Builder
    {
        if($user->can('view news')) {
            return $query;
        }

        if($user->can('view own news')) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
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

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(NewsTag::class, 'news_news_tag');
    }

    public function author()
    {
        return $this->belongsTo(NewsAuthor::class, 'author_id', 'id');
    }
}
