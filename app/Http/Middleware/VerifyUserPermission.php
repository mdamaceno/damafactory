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
        if (auth()->user()->role !== 'master' && is_null($request->header('database-token'))) {
            abort(403);
        }

        return $next($request);
    }
}
