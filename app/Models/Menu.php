<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasTranslations;

    protected $translatable = ['name', 'link'];
    protected $fillable = ['name', 'link', 'parent_id', 'order'];

}
