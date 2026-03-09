<?php

namespace App\Modules\Shared\Helpers;

use App\Modules\Shared\Services\TranslationService;
use App\Repositories\LanguageRepository;

class TranslationHelper
{
    public static function firstAvailableValue($model, string $column)
    {
        $value = $model?->getTranslation($column, app()->getLocale(), false);
        if (empty($value)) {
            $languageRepository = new LanguageRepository;
            $languages = $languageRepository->all();
            foreach ($languages as $language) {
                $value = $model?->getTranslation($column, $language->code, false);
                if (!empty($value)) {
                    $languageCode = 'translate from ' . strtoupper($language->code) . ' to ' . strtoupper(app()->getLocale()) . ': ';
                    $imageExtensions = ['gif', 'jpg', 'jpeg', 'png', 'svg', 'bmp', 'tiff', 'ico', 'webp', 'pdf', 'doc', 'docx', 'txt', 'mp3', 'mp4', 'mkv', 'ogg', 'avi', 'wmv', 'm4v'];
                    $fileExtension = pathinfo($value, PATHINFO_EXTENSION);
                    if (!in_array(strtolower($fileExtension), $imageExtensions)) {
                        if (!(str_starts_with($value, 'http://') || str_starts_with($value, 'https://'))){
                            if(config('app.translate_inputs_online') === true || config('app.translate_inputs_online') === 'true'){
                                $value = TranslationService::translate($value, $language->code);
                            }else{
                                $value = $languageCode . $value;
                            }
                        }
                    }
                    break;
                }
            }
        }

        return $value;
    }
}
