<?php

namespace App\Modules\Users\Models;

use App\Models\User;
use App\Modules\Articles\Models\Article;
use App\Modules\News\Models\News;
use App\Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name', 'nationality', 'biography'];
    protected $fillable = ['name', 'date_of_birth', 'date_of_death', 'image', 'biography', 'nationality'];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'date_of_death' => 'datetime',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id', 'id');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'author_id', 'id');
    }

    public function scopeVisibleTo($query, User $user)
    {
        if($user->can('view authors')) {
            return $query;
        }

        if($user->can('view own authors')) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }
}
