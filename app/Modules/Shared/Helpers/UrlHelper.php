<?php

namespace App\Modules\Shared\Helpers;

use App\Models\Language;

class UrlHelper
{
    public static function getFrontendUrl($path = '', $lang = '')
    {
        if (empty($lang)) {
            $lang = app()->getLocale();
        }
        $language = Language::where('code', $lang)->first();
        $url = '';
        if (! $language->use_separate_domain) {
            $url = $language->domain . '/' . $lang;
        }else{
            $url = $language->domain;
        }

        return rtrim($url, '/') . '/' . ltrim($path, '/');
    }
}
