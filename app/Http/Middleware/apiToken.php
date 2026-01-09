<?php

namespace App\Http\Middleware;

use App\Traits\generateAPI;
use Closure;
use Illuminate\Http\Request;

class apiToken
{
    use generateAPI;

    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check())
//            return $this->error([], 'Unauthorized', 401);

        return $next($request);
    }
}
