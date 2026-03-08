<?php

namespace App\Repositories;

use App\Models\Language;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

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

    public function getDefaultLanguage()
    {
        
    }

    public function getCurrentLanguage()
    {

    }
}
