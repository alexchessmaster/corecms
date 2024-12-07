<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
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
        if ($locale = $request->header('Accept-Language')) {
            App::setLocale($locale);
        }

        $response = $next($request);
        
        // $languages = Language::all();
        // if ($response->headers->get('Content-Type') === 'application/json') {
        //     // Get the original response data
        //     $data = $response->getData(true);

        //     // Append the 'languages' data to the response
        //     $data['languages'] = $languages;

        //     // Set the updated data back to the response
        //     $response->setData($data);
        // }


        return $response;
    }
}
