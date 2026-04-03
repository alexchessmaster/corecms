<?php

namespace App\Modules\Languages\Http\Middleware;

use App\Modules\Languages\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LanguageAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('lang');
        // dd($locale);
        if (empty( $locale)) {
            $locale = Language::where('default', true)->pluck('code')->first();
        }
        app()->setLocale($locale);

        return $next($request);
    }
}
