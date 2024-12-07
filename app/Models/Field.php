<?php

namespace App\Models;

use App\Models\Widget;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Field extends Model
{
    use HasTranslations;

    protected $translatable = ['value'];
    protected $guarded = [];

    public function widgets()
    {
        return $this->belongsTo(Widget::class);
    }

}
