<?php

namespace App\Modules\Shared\Helpers;

use App\Models\Language;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Repositories\LanguageRepository;
use App\Stores\SettingStore;

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
     *  $settingStore->getFullUrlBySlug($slug, $this, null, $lang) 
     *  or
     *  $settingStore->getFullUrlBySlug($slug, null, $settingStore->findByKey(SettingKeyEnum::NEWS_PREFIX, $lang), $lang) 
     * @return string ex. https://example.com/en/articles/learn/how-to-know
     */
    public static function getFullUrlBySlug(string $slug, $model = null, $prefix = '', $lang = null): string
    {
        $settingStore = new SettingStore;
        $languageRepository = new LanguageRepository;
        $languageRepository->all();
        if ($lang === null) {
            $lang = app()->getLocale();
        }
        $url = $languageRepository->getDomain($lang);
        if (!$languageRepository->useSeparateDomain()) {
            $url = $url . '/' . $lang;
        }
        if (empty($slug) || $slug === '/' || (empty($model) && empty($prefix))) {
            return $url;
        }
        if (!empty($model)) {
            $table = $model->getTable();
            if ($table === 'news') {
                $prefix = $settingStore->findByKey(SettingKeyEnum::NEWS_PREFIX, $lang);
            } else if ($table === 'books') {
                $prefix = $settingStore->findByKey(SettingKeyEnum::BOOK_PREFIX, $lang);
            } else if ($table === 'products') {
                $prefix = $settingStore->findByKey(SettingKeyEnum::PRODUCT_PREFIX, $lang);
            } else if ($table === 'articles') {
                $prefix = $settingStore->findByKey(SettingKeyEnum::ARTICLE_PREFIX, $lang);
            } else if ($table === 'pages') {
                $prefix = '';
            }
        }
        $url .= '/' . ltrim($prefix, '/');

        // later check if category hierarchy is true or false

        return $url . '/' . ltrim($slug, '/');
    }
}
