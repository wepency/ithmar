<?php

namespace App\Http\Middleware;

use App\Models\Contract;
use App\Models\Setting;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontEnd
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {

        if (auth()->check()) {
            if (auth()->user()->two_factor && !auth()->user()->factor_validated) {
                return redirect()->to('validate');
            }
        }

        view()->composer('*', function($view){

            if (!cache()->has('settings')){
                cache()->remember('settings', 888888, function (){
                    return Setting::first();
                });
            }

            if (Auth::check()) {

                $user_id = Auth::id();

                if (!cache()->has('credit-' . $user_id)) {
                    cache()->remember('credit-' . Auth::id(), 60*60, function () {
                        return Auth::user()->credit()->sum('credit');
                    });
                }

                if (!cache()->has('units-count-' . $user_id)) {
                    cache()->remember('units-count-' . Auth::id(), 60*60, function () {
                        return Auth::user()->unit()->count();
                    });
                }

                if (!cache()->has('invalid-units-count-' . $user_id)) {
                    cache()->remember('invalid-units-count-' . Auth::id(), 60*60*24, function () {
                        return Auth::user()->unit()->where('valid_to', '<=', Carbon::now())->where('type', 'investor')->count();
                    });
                }

                if (!cache()->has('blocked-units-count-' . $user_id)) {
                    cache()->remember('blocked-units-count-' . Auth::id(), 60*60*24, function () {
                        return Auth::user()->unit()->where('status', 2)->count();
                    });
                }

                if (!cache()->has('get-notifications-' . $user_id)) {
                    cache()->remember('get-notifications-' . Auth::id(), 60*60, function () {
                        return auth()->user()->notifications()->orderBy('id', 'DESC')->get();
                    });
                }

                // Contracts count
                if (!cache()->has('contracts-count-' . $user_id)) {
                    cache()->remember('contracts-count-' . Auth::id(), 60*60, function () {
                        return Auth::user()->contracts()->count();
                    });
                }

                if (!cache()->has('notifications-count-' . $user_id)) {
                    cache()->remember('notifications-count-' . Auth::id(), 60*60, function () {
                        return Auth::user()->unreadNotifications()->count();
                    });
                }

                if (!cache()->has('services-' . $user_id)) {
                    cache()->remember('services-' . Auth::id(), 8888888, function () {
                        return Auth::user()->services()->count();
                    });
                }

                if (Auth::user()->role == 'admin'){
                    if (!cache()->has('contracts-count')){
                        cache()->remember('contracts-count', 888888, function (){
                            return Contract::requests()->count();
                        });
                    }
                }

                $view->with('credit', cache()->get('credit-' . Auth::id()))
                    ->with('service_count', cache()->get('services-' . Auth::id()));
            }
        });

        return $next($request);
    }
}
