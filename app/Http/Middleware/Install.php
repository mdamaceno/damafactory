<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class Install
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
        if (!User::count()) {
            return redirect('/install');
        }

        return $next($request);
    }
}
