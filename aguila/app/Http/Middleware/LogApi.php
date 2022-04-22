<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogApi
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
        Log::channel('movil')->info('Api-Activity', [
            'ip'         => $request->ip(),
            'uri'        => $request->path(),
            'method'     => $request->method(),
            'route-name' => $request->route() ? $request->route()->getName() : '',
            'request'    => $request->except(['_token', 'password', 'events', 'items']),
        ]);
        return $next($request);
    }
}
