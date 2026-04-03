<?php

namespace App\Modules\News\Models;

use App\Modules\Languages\Models\Language;
use App\Modules\Settings\Models\Setting;
use App\Models\User;
use App\Modules\Widgets\Models\Widget;
use App\Modules\Widgets\Models\Widgetable;
use App\Modules\News\Models\NewsAuthor;
use App\Modules\News\Models\NewsCategory;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Modules\Languages\Repositories\LanguageRepository;
use App\Modules\Settings\Repositories\SettingRepository;
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
    public $translatable = ['title', 'slug', 'description', 'image', 'image_medium', 'image_thumbnail'];
    protected $casts = [
        'scheduled_at' => 'datetime',
        'news_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
        if ($user->can('view news')) {
            return $query;
        }

        if ($user->can('view own news')) {
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
