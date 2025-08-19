<?php

namespace App\Models;

use App\Models\Widget;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Language;
use App\Models\BookAuthor;
use App\Models\Widgetable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'slug', 'description', 'image'];
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

        $bookPrefix = cache()->remember('book-prefix', 3600, function () {
            return Setting::where('key', 'book-prefix')->value('value');
        });

        if (!empty($bookPrefix)) {
            $bookPrefix = '/' . trim($bookPrefix, '/');
            $fullUrl = $bookPrefix . $fullUrl;
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
        return $this->belongsTo(BookAuthor::class, 'author_id');
    }
}
