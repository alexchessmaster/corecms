<?php

namespace App\Modules\Products\Models;

use App\Models\User;
use App\Modules\Widgets\Models\Widget;
use App\Modules\Widgets\Models\Widgetable;
use App\Modules\Users\Models\Author;
use App\Modules\Products\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'slug', 'description', 'image', 'image_medium', 'image_thumbnail'];
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

    public function scopeVisibleTo($query, User $user): Builder
    {
        if($user->can('view products')) {
            return $query;
        }

        if($user->can('view own products')) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    public function widgetables(): MorphMany
    {
        return $this->morphMany(Widgetable::class, 'widgetable')->orderBy('position');
    }

    public function widgets(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
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

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_pivot', 'product_id', 'product_tag_id');
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }
}
