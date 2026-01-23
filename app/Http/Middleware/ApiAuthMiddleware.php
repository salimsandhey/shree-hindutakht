<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if member is authenticated via API guard
        if (!Auth::guard('api')->check()) {
            // For API requests, always return JSON response
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Member authentication required'
            ], 401);
        }

        return $next($request);
    }
}