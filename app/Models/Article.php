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

    protected $fillable = ['image', 'title', 'slug', 'content', 'category_id', 'description'];
    public $translatable = ['title', 'slug','content', 'description'];

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
