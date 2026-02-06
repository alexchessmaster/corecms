<?php

namespace App\Modules\Booking\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && (auth()->user()->hasRole('super_admin') && auth()->user()->hasRole('admin'))) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
