<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->role !== 'master') {
            return response()->json(['error' => 'not_found'], 404);
        }

        return $next($request);
    }
}
