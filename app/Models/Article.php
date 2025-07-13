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

    protected $fillable = ['image', 'title', 'title_seo', 'slug', 'content', 'category_id', 'description', 'description_seo', 'sitemap_exclude', 'sitemap_priority', 'sitemap_change_frequency', 'primary_language'];
    public $translatable = ['title', 'title_seo', 'slug','content', 'description', 'description_seo'];

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
        
        $languages = Language::all();
        $multipleLanguages = cache()->remember('is-multiple-languages', 3600, function () use ($languages) {
            return count($languages) > 1;
        });
    
        if ($multipleLanguages) {
            $lang = app()->getLocale();
            if(! $languages->value('use_separate_domain')){
                $fullUrl = '/' . $lang . $fullUrl;
            }
        }
    
        return $fullUrl;
    }
}
