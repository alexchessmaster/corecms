<?php

namespace App\Models;

use App\Models\Widget;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    protected $translatable = ['title', 'slug'];
    protected $guarded = [];

    public function widgets()
    {
        return $this->belongsToMany(Widget::class)
                    ->withPivot('position')
                    ->withTimestamps();
    }

    public function pageWidgets()
    {
        return $this->hasMany(PageWidget::class);
    }
}
