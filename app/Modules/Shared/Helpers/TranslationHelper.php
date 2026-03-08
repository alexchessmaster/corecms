<?php

namespace App\Modules\Shared\Helpers;

use App\Repositories\LanguageRepository;

class TranslationHelper
{
    public static function firstAvailableValue($model, string $column)
    {
        $file = $model->getTranslation('image', app()->getLocale());
        if (empty($file)) {
            $languageRepository = new LanguageRepository;
            $languages = $languageRepository->all();
            foreach ($languages as $language) {
                $file = $model->getTranslation('image', $language->code);
                if (!empty($file)) {
                    break;
                }
            }
        }

        return $file;
    }
}
