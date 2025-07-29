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
            'default' => true,
            'use_separate_domain' => false,
            'domain' => 'https://azadandish.net'
        ],
        [
            'name' => 'German', 
            'code' => 'de',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://azadandish.net'
        ],
        [
            'name' => 'French', 
            'code' => 'fr',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://azadandish.net'
        ],
        [
            'name' => 'Danish', 
            'code' => 'da',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://azadandish.net'
        ],
        [
            'name' => 'Farsi', 
            'code' => 'fa',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://azadandish.net'
        ],
    ];
}
