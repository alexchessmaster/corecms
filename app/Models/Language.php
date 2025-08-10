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
            'domain' => 'https://atheistlibrary.net/en'
        ],
        [
            'name' => 'German', 
            'code' => 'de',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://atheistlibrary.net/de'
        ],
        [
            'name' => 'French', 
            'code' => 'fr',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://atheistlibrary.net/fr'
        ],
        [
            'name' => 'Danish', 
            'code' => 'da',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://atheistlibrary.net/da'
        ],
        [
            'name' => 'Swedish', 
            'code' => 'sv',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://atheistlibrary.net/sv'
        ],
        [
            'name' => 'Persian', 
            'code' => 'fa',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://atheistlibrary.net/fa'
        ],
    ];
}
