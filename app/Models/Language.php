<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use \Sushi\Sushi;

    protected $rows = [
        [
            'name' => 'English', 
            'code' => 'en', 
            'default' => false
        ],
        [
            'name' => 'Danish',
            'code' => 'da',
            'default' => true
        ],
    ];
}
