<?php

namespace App\Modules\News\Models;

use App\Models\Language;
use App\Models\Setting;
use App\Models\User;
use App\Models\Widget;
use App\Models\Widgetable;
use App\Modules\News\Models\NewsAuthor;
use App\Modules\News\Models\NewsCategory;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Repositories\LanguageRepository;
use App\Stores\SettingStore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'slug', 'description', 'image'];
    protected $casts = [
        'scheduled_at' => 'datetime',
        'news_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // /en/news/category1/the-news-title
    public function getFullUrlAttribute()
    {
        $fullUrl = $this->slug;
        $languageRepository = new LanguageRepository;
        $languages = $languageRepository->all();
        $settingStore = new SettingStore;
        $newsPrefix = $settingStore->findByKey(SettingKeyEnum::NEWS_PREFIX);
        $multipleLanguages = $settingStore->isTranslatable(SettingKeyEnum::NEWS_PREFIX);
        if (!empty($newsPrefix)) {
            $newsPrefix = '/' . trim($newsPrefix, '/');
            $fullUrl = $newsPrefix . $fullUrl;
        }
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
