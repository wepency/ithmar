<?php

namespace App\Providers;

use App\Models\Contract;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        include_once app_path('Helpers/constants.php');

        Carbon::setLocale('ar');
        Paginator::useBootstrap();

        view()->composer('*', function($view){

            if (!cache()->has('settings')){
                cache()->remember('settings', 888888, function (){
                    return Setting::first();
                });
            }

            if (!cache()->has('vertime')){
                cache()->remember('vertime', 888888, function (){
                    return Setting::first()->ver_time;
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
//                        return auth()->user()->notifications()->orderBy('id', 'DESC')->get();
                        return [];
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
//                        return Auth::user()->unreadNotifications()->count();
                        return 0;
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

//        Permission::create(['name' => 'can view history sectors']);
//        Permission::create(['name' => 'can view history beaches']);
//        Permission::create(['name' => 'can view history units']);
//        Permission::create(['name' => 'can view history units requests']);
//        Permission::create(['name' => 'can view history contracts']);
//        Permission::create(['name' => 'can view history contracts requests']);
//        Permission::create(['name' => 'can view history clients']);
//        Permission::create(['name' => 'can view history permissions']);
//        Permission::create(['name' => 'can filter contracts']);
//        Permission::create(['name' => 'can view contract']);
//        Permission::create(['name' => 'can view clients']);
//        Permission::create(['name' => 'can view full user history']);


//        Permission::create(['name' => 'can view bonds']);
//        Permission::create(['name' => 'can add bond']);
//        Permission::create(['name' => 'can filter bonds']);
//        Permission::create(['name' => 'can view history bonds']);

        // Can See Permission
        //// Can add Permissions
        //// Can edit Permissions

        /// Can see Settings

        /// Cache credit

    }
}
