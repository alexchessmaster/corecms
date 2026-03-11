<?php

namespace App\Modules\Books\Models;

use App\Models\Language;
use App\Models\User;
use App\Models\Widget;
use App\Models\Widgetable;
use App\Modules\Books\Models\BookAuthor;
use App\Modules\Books\Models\BookGenre;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Repositories\LanguageRepository;
use App\Stores\SettingStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'slug', 'description', 'image', 'image_medium', 'image_thumbnail'];
    protected $casts = [
        'scheduled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function bookGenre()
    {
        return $this->belongsTo(BookGenre::class);
    }

    public function getFullUrlAttribute()
    {
        $fullUrl = $this->slug;

        $languageRepository = new LanguageRepository;
        $languages = $languageRepository->all();

        $settingStore = new SettingStore;
        $bookPrefix = $settingStore->findByKey(SettingKeyEnum::BOOK_PREFIX);

        $multipleLanguages = $settingStore->isTranslatable(SettingKeyEnum::BOOK_PREFIX);

        if (!empty($bookPrefix)) {
            $bookPrefix = '/' . trim($bookPrefix, '/');
            $fullUrl = $bookPrefix . $fullUrl;
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

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->can('view books')) {
            return $query;
        }

        if ($user->can('view own books')) {
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

    public function author()
    {
        return $this->belongsTo(BookAuthor::class, 'author_id', 'id');
    }
}
