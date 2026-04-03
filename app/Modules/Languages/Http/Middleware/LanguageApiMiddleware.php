<?php

namespace App\Modules\Languages\Http\Middleware;

use Log;
use Closure;
use App\Modules\Languages\Models\Language;
use Illuminate\Http\Request;
use function Laravel\Prompts\info;

use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LanguageApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($acceptLanguage = $request->header('Accept-Language')) {
            // Parse the Accept-Language header to get the first locale
            // Example: "en_US,en;q=0.9,da;q=0.8" -> "en_US"
            $locale = explode(',', $acceptLanguage)[0];
            // Remove quality values if present (e.g., "en;q=0.9" -> "en")
            $locale = explode(';', $locale)[0];
            // Convert locale format (en_US -> en, en-US -> en)
            $locale = strtolower(explode('_', explode('-', $locale)[0])[0]);

            if (!empty($locale)) {
                App::setLocale($locale);
            }
        }

        if (!empty($request->lang)) {
            App::setLocale($request->lang);
        }

        $response = $next($request);

        return $response;
    }
}
