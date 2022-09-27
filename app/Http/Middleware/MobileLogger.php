<?php

namespace App\Http\Middleware;

use Closure;

class MobileLogger
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
        $request_array = $request->all();
        $request->replace($request_array);
        \Log::info($request->method()                                                                                                                              );
        \Log::info($request->url());
        \Log::info($request);

        return $next($request);
    }
}
