<?php

namespace App\Modules\News\Models;

use App\Models\User;
use App\Modules\News\Models\News;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NewsTag extends Model
{
    /** @use HasFactory<\Database\Factories\NewsTagFactory> */
    use HasFactory;
    use HasTranslations;

    protected $fillable = ['name', 'slug'];
    public $translatable = ['name', 'slug'];

    public function news()
    {
        return $this->belongsToMany(News::class);
    }

    public function scopeVisibleTo($query, User $user): Builder
    {
        if($user->can('view news tags')) {
            return $query;
        }

        if($user->can('view own news tags')) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }
}
