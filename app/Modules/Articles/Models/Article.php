<?php

namespace App\Modules\Articles\Models;

use App\Models\User;
use App\Modules\Users\Models\Author;
use App\Modules\Widgets\Models\Widget;
use App\Modules\Widgets\Models\Widgetable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

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

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }
}
