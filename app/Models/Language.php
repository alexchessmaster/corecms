<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use \Sushi\Sushi;

    protected $rows = [
        [
            'name' => 'Farsi', 
            'code' => 'fa',
            'default' => true,
            'use_separate_domain' => true,
            'domain' => 'https://azadandish.net'
        ],
    ];
}
