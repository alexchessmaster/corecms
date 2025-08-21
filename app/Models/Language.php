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
            'domain' => 'https://atheistlibrary.com'
        ],
        // [
        //     'name' => 'German', 
        //     'code' => 'de',
        //     'default' => false,
        //     'use_separate_domain' => false,
        //     'domain' => 'https://atheistlibrary.com'
        // ],
        // [
        //     'name' => 'French', 
        //     'code' => 'fr',
        //     'default' => false,
        //     'use_separate_domain' => false,
        //     'domain' => 'https://atheistlibrary.com'
        // ],
        // [
        //     'name' => 'Danish', 
        //     'code' => 'da',
        //     'default' => false,
        //     'use_separate_domain' => false,
        //     'domain' => 'https://atheistlibrary.com'
        // ],
        // [
        //     'name' => 'Swedish', 
        //     'code' => 'sv',
        //     'default' => false,
        //     'use_separate_domain' => false,
        //     'domain' => 'https://atheistlibrary.com'
        // ],
        [
            'name' => 'Persian', 
            'code' => 'fa',
            'default' => false,
            'use_separate_domain' => false,
            'domain' => 'https://atheistlibrary.com'
        ],
    ];
}
