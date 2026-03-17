<?php

namespace App\Repositories;

use App\Models\Language;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Log;

class LanguageRepository
{
    private Collection $allLanguages;

    public function __construct()
    {
        $this->allLanguages = Language::all();
    }

    public function all(): Collection
    {
        return $this->allLanguages;
    }

    public function findById(int $id)
    {
        $language = $this->allLanguages->firstWhere('id', $id);

        return $language;
    }

    public function findByCode($code)
    {
        $language = $this->allLanguages->firstWhere('code', $code->value);

        return $language;
    }

    public function isMultiLanguage(): bool
    {
        return count($this->allLanguages) > 1 ? true : false;
    }

    public function useSeparateDomain(): bool
    {
        return (bool)$this->allLanguages->first()->use_separate_domain;
    }

    public function getDefaultLanguage() {}

    public function getCurrentLanguage() {}

    /**
     * @param string $lang ex. 'en', 'da'
     */
    public function getDomain(?string $lang = null): string
    {
        if ($lang === null) {
            $lang = app()->getLocale();
        }
        $domain = $this->allLanguages->firstWhere('code', $lang)?->domain;
        if (empty($domain)) {
            Log::info('Domain not found in getDomain method in LanguageRepository.');
        }
        if (str_starts_with($domain, 'http://') || str_starts_with($domain, 'https://')) {
            return $domain;
        }

        return 'https://' . $domain;
    }
}
