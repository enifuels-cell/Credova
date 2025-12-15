<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LandlordMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || (!auth()->user()->isLandlord() && !auth()->user()->isAdmin())) {
            abort(403, 'Unauthorized. Landlord access required.');
        }

        return $next($request);
    }
}
