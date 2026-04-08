<?php

namespace App\Modules\Shared\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TranslationService
{
    public static function translate(string $value, string $fromLang, string $toLang = ""): string
    {
        if (empty($toLang)) {
            $toLang = app()->getLocale();
        }
        $prompt = "Translate the following text after the pipe line from $fromLang to $toLang and only return the translated text | ";
        Log::info('translate from: ' . $value);

        try {
            $response = Http::connectTimeout(30)
                ->timeout(30)
                ->post('https://poolai-backend.nordicstandard.net/chat', [
                    'q' => $prompt . ' ' . $value,
                ]);
        } catch (RequestException $e) {
            Log::error('translate request failed', [
                'message' => $e->getMessage(),
                'status' => $e->response?->status(),
                'body' => $e->response?->body(),
            ]);

            return "Error while translating from $fromLang to $toLang:  " . $value;
        } catch (Throwable $e) {
            Log::error('translate failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return "Error while translating from $fromLang to $toLang: " . $value;
        } catch (\Exception $e) {
            // Handles connection issues, DNS errors, timeouts, etc.
            Log::error('translate failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return "Error while translating from $fromLang to $toLang:" . $value;
        }

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
