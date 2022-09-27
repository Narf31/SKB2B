<?php

namespace App\Http\Middleware;

use Closure;

class CheckCabinetKey
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
        if ($request->key != config('schemes.logchange.key')) {
            return abort(403, 'Permission denied');
        }
        return $next($request);

    }
}