<?php

namespace App\Modules\TranslationTexts\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TranslationText extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public $translatable = ['text'];
}
