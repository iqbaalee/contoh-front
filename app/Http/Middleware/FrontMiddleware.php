<?php

namespace App\Http\Middleware;

use Closure;

class FrontMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!$request->hasCookie('X-PERSONAL') && !$request->hasCookie('X-FOOTSAL')) {
            return redirect()->route('auth.login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        } else {
            return $next($request);
        }
    }
}
