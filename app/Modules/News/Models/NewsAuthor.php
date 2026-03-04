<?php

namespace App\Modules\News\Models;

use App\Models\User;
use App\Modules\News\Models\News;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsAuthor extends Model
{
    /** @use HasFactory<\Database\Factories\NewsAuthorFactory> */
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name', 'nationality', 'biography'];
    protected $fillable = ['name', 'date_of_birth', 'date_of_death', 'image', 'biography', 'nationality'];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'date_of_death' => 'datetime',
    ];

    public function news()
    {
        return $this->hasMany(News::class, 'author_id', 'id');
    }

    public function scopeVisibleTo($query, User $user)
    {
        if($user->can('view news authors')) {
            return $query;
        }

        if($user->can('view own news authors')) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }
}
