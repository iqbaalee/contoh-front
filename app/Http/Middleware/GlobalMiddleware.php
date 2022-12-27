<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GlobalMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if ($request->hasCookie('X-PERSONAL') &&  $request->hasCookie('X-FOOTSAL')) {
            return redirect()->route('dashboard.index');
        } else {
            return $next($request);
        }
    }
}
