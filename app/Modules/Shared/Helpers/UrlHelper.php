<?php

namespace App\Modules\Shared\Helpers;

use App\Modules\Languages\Models\Language;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Modules\Languages\Repositories\LanguageRepository;
use App\Modules\Settings\Repositories\SettingRepository;

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

    /** Generate slug from title without non-standard characters*/
    public static function generateSlug(string $string): string
    {
        $replaceSlugLetters = [

            // Danish / Norwegian
            'æ' => 'ae',
            'Æ' => 'Ae',
            'ø' => 'o',
            'Ø' => 'O',
            'å' => 'aa',
            'Å' => 'Aa',

            // Swedish
            'ä' => 'a',
            'Ä' => 'A',
            'ö' => 'o',
            'Ö' => 'O',

            // German
            'ä' => 'ae',
            'Ä' => 'Ae',
            'ö' => 'oe',
            'Ö' => 'Oe',
            'ü' => 'ue',
            'Ü' => 'Ue',
            'ß' => 'ss',

            // Dutch
            'é' => 'e',
            'É' => 'E',
            'è' => 'e',
            'È' => 'E',
            'ë' => 'e',
            'Ë' => 'E',
            'ï' => 'i',
            'Ï' => 'I',

            // French
            'à' => 'a',
            'À' => 'A',
            'â' => 'a',
            'Â' => 'A',
            'ç' => 'c',
            'Ç' => 'C',
            'é' => 'e',
            'É' => 'E',
            'è' => 'e',
            'È' => 'E',
            'ê' => 'e',
            'Ê' => 'E',
            'ë' => 'e',
            'Ë' => 'E',
            'î' => 'i',
            'Î' => 'I',
            'ï' => 'i',
            'Ï' => 'I',
            'ô' => 'o',
            'Ô' => 'O',
            'ù' => 'u',
            'Ù' => 'U',
            'û' => 'u',
            'Û' => 'U',
            'ü' => 'u',
            'Ü' => 'U', // (French usage)

            // Persian numbers → Latin
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',

        ];
        $string = strtr($string, $replaceSlugLetters);

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

    /**
     * @param string $slug
     * @param $model either this should have value or $prefix
     * @param string|null $lang
     * use:
     *  $settingRepository->getFullUrlBySlug($slug, $this, null, $lang)
     *  or
     *  $settingRepository->getFullUrlBySlug($slug, null, $settingRepository->findByKey(SettingKeyEnum::NEWS_PREFIX, $lang), $lang)
     * @return string ex. https://example.com/en/articles/learn/how-to-know
     */
    public static function getFullUrlBySlug(string $slug, $modelOrTable = null, $prefix = null, $lang = null, $onlyPath = false): string
    {
        if($modelOrTable !== null){
            if (gettype($modelOrTable) === 'string') {
                $table = $modelOrTable;
            } else {
                $table = $modelOrTable->getTable();
            }
        }
        $settingRepository = app(SettingRepository::class);
        $languageRepository = app(LanguageRepository::class);
        $languageRepository->all();
        if ($lang === null) {
            $lang = app()->getLocale();
        }
        $url = '';
        if(empty($onlyPath)){
            $url = $languageRepository->getDomain($lang);
            if (!$languageRepository->useSeparateDomain()) {
                $url = $url . '/' . $lang;
            }
        }
        if (empty($slug) || $slug === '/' || (empty($modelOrTable) && empty($prefix))) {
            return $url;
        }
        if (!empty($modelOrTable)) {
            if ($table === 'news') {
                $prefix = $settingRepository->findByKey(SettingKeyEnum::NEWS_PREFIX, $lang);
            } else if ($table === 'books') {
                $prefix = $settingRepository->findByKey(SettingKeyEnum::BOOK_PREFIX, $lang);
            } else if ($table === 'products') {
                $prefix = $settingRepository->findByKey(SettingKeyEnum::PRODUCT_PREFIX, $lang);
            } else if ($table === 'articles') {
                $prefix = $settingRepository->findByKey(SettingKeyEnum::ARTICLE_PREFIX, $lang);
            } else if ($table === 'pages') {
                $prefix = '';
            }
        }
        $prefix = trim($prefix, '/');
        if(!empty($prefix)){
            $prefix = '/' . $prefix;
        }
        $url .= $prefix;
        $url .= '/' . trim($slug, '/');

        // later check if category hierarchy is true or false
        return $url;
    }
}
