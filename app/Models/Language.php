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
            'default' => false,
            'use_separate_domain' => true,
            'domain' => 'https://nordicstandard.net'
        ],
        [
            'name' => 'Danish',
            'code' => 'da',
            'default' => true,
            'use_separate_domain' => true,
            'domain' => 'https://nordicstandard.dk'
        ],
    ];
}
