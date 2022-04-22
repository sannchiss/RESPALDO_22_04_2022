<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!\Permission::hasPermission($request->route()->getName(), true) ) {
            abort(401, 'Unauthorized user.');
        }

        return $next($request);
    }

}