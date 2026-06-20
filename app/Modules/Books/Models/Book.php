<?php

namespace App\Modules\Books\Models;

use App\Modules\Languages\Models\Language;
use App\Models\User;
use App\Modules\Widgets\Models\Widget;
use App\Modules\Widgets\Models\Widgetable;
use App\Modules\Books\Models\BookAuthor;
use App\Modules\Books\Models\BookGenre;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Modules\Languages\Repositories\LanguageRepository;
use App\Modules\Settings\Repositories\SettingRepository;
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
    public $translatable = ['title', 'slug', 'pdf','description', 'image', 'image_medium', 'image_thumbnail', 'page_image_folder'];
    protected $casts = [
        'scheduled_at' => 'datetime',
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

    public function bookGenre()
    {
        return $this->belongsTo(BookGenre::class);
    }

    public function author()
    {
        return $this->belongsTo(BookAuthor::class, 'author_id', 'id');
    }
}
