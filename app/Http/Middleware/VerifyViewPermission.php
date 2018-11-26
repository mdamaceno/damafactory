<?php

namespace App\Http\Middleware;

use Closure;

class VerifyViewPermission
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
            alert()->error(__('You are not permitted!'));
            return redirect('admin/databases');
        }

        return $next($request);
    }
}
