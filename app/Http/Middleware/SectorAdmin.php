<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SectorAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (is_admin() || is_sector_admin()){
            return $next($request);
        }

        session(['url.intended' => url()->current()]);
        return redirect()->to('/');
    }
}
