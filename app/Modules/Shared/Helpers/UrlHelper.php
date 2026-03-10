<?php

namespace App\Modules\Shared\Helpers;

use App\Models\Language;

class UrlHelper
{
    public static function isUrlEncoded($str)
    {
        return $str !== rawurldecode($str);
    }

    public static function getFrontendUrl($path = '', $lang = '', $betweenLangAndPath = '')
    {
        if (empty($lang)) {
            $lang = app()->getLocale();
        }
        $language = Language::where('code', $lang)->first();
        $url = '';
        if (! $language->use_separate_domain) {
            $url = $language->domain . '/' . $lang;
        } else {
            $url = $language->domain;
        }
        if (!empty($betweenLangAndPath)) {
            $betweenLangAndPath = '/' . ltrim($betweenLangAndPath, '/');
            $betweenLangAndPath = rtrim($betweenLangAndPath, '/');
        }
        $url = rtrim($url, '/') . $betweenLangAndPath . '/' . ltrim($path, '/');
        $url = ltrim($url, '/');
        $url = 'https://' . ltrim($url, 'https://');

        return $url;
    }

    /** Generate slug from title */
    public static function generateSlug(string $string): string
    {
        // 1. Normalize UTF-8 characters (optional, keeps letters readable)
        // $string = iconv('UTF-32', 'ASCII//TRANSLIT//IGNORE', $string);
        // 1. Remove all unwanted characters (punctuation, symbols, zero-width)
        $string = preg_replace('/[^\p{L}\p{Nd}\s-]/u', '', $string);
        // 2. Replace spaces and underscores with hyphens
        $string = preg_replace('/[\s_]+/', '-', $string);
        // 3. Remove multiple hyphens
        $string = preg_replace('/-+/', '-', $string);
        // 4. Trim hyphens from start and end
        $string = trim($string, '-');
        // 5. Lowercase
        $string = strtolower($string);

        return $string;
    }
}
