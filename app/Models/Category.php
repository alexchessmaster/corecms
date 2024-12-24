<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'slug', 'description', 'parent_id'];
    public $translatable = ['name', 'slug', 'description'];

// protected static function boot()
// {
//     parent::boot();
//     static::creating(function($category){
//         if(empty($category->slug)){
//             // TODO: we need slug-2 slug-3 and ... if duplicated
//             $category->setTranslation('slug', app()->getLocale(), Str::slug($category->name));
//         }
//     });
// }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
