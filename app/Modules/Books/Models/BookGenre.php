<?php

namespace App\Modules\Books\Models;

use App\Models\User;
use App\Modules\Books\Models\Book;
use App\Models\Widget;
use App\Models\Widgetable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookGenre extends Model
{
    /** @use HasFactory<\Database\Factories\BookGenreFactory> */
    use HasFactory;
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['name', 'slug', 'description', 'image'];
    protected $with = ['parent'];

    protected $casts = [
        'hide_from_frontend' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(BookGenre::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BookGenre::class, 'parent_id');
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function scopeWithAllWidgetData($query)
    {
        return $this->with([
            'widgetables.widget.fieldWidgets.field',
            'widgetables.widgetFieldValues.fieldWidget.field',
        ]);
    }

    public function scopeVisibleTo($query, User $user)
    {
        if($user->can('view book genres')) {
            return $query;
        }

        if($user->can('view own book genres')) {
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
