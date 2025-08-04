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
            'domain' => 'https://azadandish.net/en'
        ],
        [
            'name' => 'German', 
            'code' => 'de',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://azadandish.net/de'
        ],
        [
            'name' => 'French', 
            'code' => 'fr',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://azadandish.net/fr'
        ],
        [
            'name' => 'Danish', 
            'code' => 'da',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://azadandish.net/da'
        ],
        [
            'name' => 'Swedish', 
            'code' => 'sv',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://azadandish.net/sv'
        ],
        [
            'name' => 'Persian', 
            'code' => 'fa',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://azadandish.net/fa'
        ],
    ];
}
