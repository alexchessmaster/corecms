<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Models\Article;

class Tag extends Model
{
    use HasTranslations;

    protected $fillable = ['name'];
    public $translatable = ['name'];

    public function scopeVisibleTo($query, User $user)
    {
        if($user->can('view tags')) {
            return $query;
        }

        if($user->can('view own tags')) {
            return $query->where('user_id', $user->id);
        }
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
