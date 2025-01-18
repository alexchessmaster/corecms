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

    protected $fillable = ['image', 'title', 'slug', 'content', 'category_id', 'description', 'sitemap_exclude', 'sitemap_priority', 'sitemap_change_frequently'];
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

    public function getFullUrlAttribute()
    {
        $fullUrl = $this->slug;
    
        $articlePrefix = cache()->remember('article-prefix', 3600, function () {
            return Setting::where('key', 'article-prefix')->value('value');
        });
    
        if (!empty($articlePrefix)) {
            $articlePrefix = '/' . trim($articlePrefix, '/');
            $fullUrl = $articlePrefix . $fullUrl;
        }
    
        $multipleLanguages = cache()->remember('is-multiple-languages', 3600, function () {
            return Language::count() > 1;
        });
    
        if ($multipleLanguages) {
            $lang = app()->getLocale();
            $fullUrl = '/' . $lang . $fullUrl;
        }
    
        return $fullUrl;
    }
}
