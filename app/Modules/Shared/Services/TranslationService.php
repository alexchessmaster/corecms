<?php

namespace App\Modules\Shared\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    public static function translate(string $value, string $fromLang, string $toLang = ""): string
    {
        if (empty($toLang)) {
            $toLang = app()->getLocale();
        }
        $prompt = "Translate the following text after semicolon from $fromLang to $toLang and only return the translated text;";
        Log::info('translate from: ' . $value);
        
        $response = Http::post('https://poolai-backend.nordicstandard.net/chat', [
            'q' => $prompt . ' ' . $value,
        ]);

        if ($response->successful()) {
            $translated = $response->body();
            // Remove extra quotes or apostrophes at start/end
            // $translated = trim($translated, "\"'");

            Log::info('translate successful: ' . $translated);
            return $translated;
        }
        Log::info('translate failed: ' . json_encode($response->body()));

        // Fallback on error
        return "Error: " . $response->body();
    }
}
