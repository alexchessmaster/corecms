<?php

namespace App\Modules\Shared\Helpers;

use App\Models\Language;

class UrlHelper
{
    public static function getFrontendUrl($path = '', $lang = '', $betweenLangAndPath = '')
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
        if(!empty($betweenLangAndPath)){
            $betweenLangAndPath = '/' . ltrim($betweenLangAndPath, '/');
            $betweenLangAndPath = rtrim($betweenLangAndPath, '/');
        }
        $url = rtrim($url, '/') . $betweenLangAndPath . '/' . ltrim($path, '/');
        $url = ltrim($url, '/');
        $url = 'https://' . ltrim($url, 'https://');

        return $url;
    }
}
