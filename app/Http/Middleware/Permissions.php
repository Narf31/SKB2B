<?php

namespace App\Http\Middleware;

use Closure;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $permissionGroupName, $permissionName)
    {
        if (!auth()->check() || !auth()->user()->role) {

            return abort(403, 'Permission denied');

        }

        if (!auth()->user()->hasPermission($permissionGroupName, $permissionName)) {

            return abort(403, 'Permission denied');

        }

        return $next($request);
    }
}
