<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Article extends Model
{
    use HasTranslations;

    protected $fillable = ['image', 'title', 'slug', 'content', 'category_id'];
    public $translatable = ['title', 'slug','content'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($article){
            if(empty($article->slug)){
                // TODO: we need slug-2 slug-3 and ... if duplicated
                $article->setTranslation('slug', app()->getLocale(), Str::slug($article->title));
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'template_page_id', 'id');
    }
}
