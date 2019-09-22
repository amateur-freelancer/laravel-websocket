<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    function handle($request, Closure $next)
    {
        if (JWTAuth::check() && JWTAuth::user()->role == 'admin') {
            return $next($request);
        }
        else {
            return response()->json([
                'status' => 'Forbidden', 
                'message' => 'Permission denied.'
            ], 403);
        }
    }
 
}
