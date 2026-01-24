<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_verified) {
            // Allow access to pending verification page and logout
            if (!$request->routeIs('pending-verification') && !$request->routeIs('logout')) {
                return redirect()->route('pending-verification');
            }
        }

        return $next($request);
    }
}
