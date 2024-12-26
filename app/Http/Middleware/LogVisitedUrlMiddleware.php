<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use Exception;
use App\Models\UrlLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogVisitedUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $url = urldecode($request->fullUrl());
        $referer_url = null;
        if(!empty(request()->headers->get('referer'))){
            $referer_url = urldecode(request()->headers->get('referer'));
        }

        try {
            if(!(Auth::user()?->is_admin)){
                UrlLog::create([
                    'user_id' => Auth::id(),
                    'params' => substr(json_encode($request->all()), 0, 255),
                    'url' => substr($request->fullUrl(),0, 255),
                    'http_method' => substr($request->method(),0, 255),
                    'ip_address' => substr($ip,0, 255),
                    'user_agent' => substr($request->header('User-Agent'),0, 255),
                    'referrer' => substr($request->header('Referer'),0, 255),
                ]);
            }
        } catch (Exception $e) {
            // Log the error
            Log::error('Error logging URL in LogVisitedUrlMiddleware handle: ' . $e->getMessage());
        }
        
        return $next($request);
    }
}
