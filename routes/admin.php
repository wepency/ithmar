<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'dashboard'], function () {
  Route::get('/login', 'Admin\authController@loginForm');
  Route::post('/login', 'Admin\authController@login')->name('admin.login');
});

Route::group(['prefix' => 'dashboard','as' => 'admin.', 'middleware' => 'FrontEnd'], function (){

      Route::get('/', 'Admin\DashboardController');

      Route::get('users/{user}/history', 'Admin\UsersController@history');

      Route::put('user/{id}/unitStatus', 'Admin\UsersController@unitStatus');
      Route::resource('users', 'Admin\UsersController')->except('create', 'edit', 'destroy');
      Route::post('users/switch/{id}', 'Admin\UsersController@switchBlock')->name('switch.block');

      Route::resource('beaches', 'Admin\BeachesController');
      Route::resource('sector', 'Admin\SectorsController');

      Route::get('contracts/requests', 'Admin\contractRequestsController@requests');
      Route::put('contracts/request/{contract}/{status}', 'Admin\contractRequestsController@acceptContract');

      Route::put('contract/{contract_id}/changeStatus', 'Admin\ContractsController@changeStatus')->name('contract.changeStatus');
      Route::put('contract/{contract_id}/checkCode', 'Admin\ContractsController@checkCode');
      Route::post('contract/{contract_id}/resendCode', 'Admin\ContractsController@resendCode');
      Route::put('contract/{id}/{cancel}', 'Admin\ContractsController@cancel');
      Route::get('contract/show/{code}', 'Admin\ContractsController@show');
      Route::get('contract/{id}/history', 'Admin\ContractsController@getHistory');
      Route::get('contract/{contract_id}/validateNumber', 'Admin\ContractsController@validateNumber');

      Route::resource('contract', 'Admin\ContractsController');

      Route::get('reports/services', 'Admin\ServicesReportsController@get');

      Route::resource('reports', 'Admin\ReportsController');
      Route::resource('units', 'Admin\UnitsController');
      Route::get('attachments/{attachment_id}', 'Admin\attachmentsController@show');
      Route::resource('permissions', 'Admin\permissionsController');
      // Services
      Route::resource('services', 'Admin\servicesController')->except('edit', 'show', 'create');

      Route::get('requests', 'Admin\DashboardController@requests')->name('requests');
      Route::put('request/terminate/{unit_id}', 'Admin\DashboardController@terminate')->name('request.terminate');
      Route::put('request/status/{type}/{id}', 'Admin\DashboardController@request_status')->name('request.status');

      Route::get('bonds', 'Admin\bondsController@index');
      Route::get('bonds/{id}', 'Admin\bondsController@show');
      Route::post('bonds', 'Admin\bondsController@store')->name('bonds.store');
      Route::delete('bonds/{id}', 'Admin\bondsController@destroy');
      Route::get('bonds/{id}/contracts', 'Admin\bondsController@contracts');
      Route::get('bonds/{id}/export', 'Admin\bondsController@export');

      Route::put('bonds/{id}/change/{status}', 'Admin\bondsController@changeStatus')->name('bonds.status.update');
      Route::put('bonds/{id}', 'Admin\bondsController@update')->name('bonds.update');

      Route::get('settings', 'Admin\SettingsController@form');
      Route::put('settings', 'Admin\SettingsController@save');

      Route::get('notifications', 'Admin\notificationsController@index');

      Route::get('user/edit', 'Admin\AdminController@edit');
      Route::put('user/update', 'Admin\AdminController@update');

      Route::get('user/wallet/{id}', 'Admin\UserWalletController@index')->name('user.wallet.index');
      Route::post('user/wallet/{id}', 'Admin\UserWalletController@store')->name('user.wallet.store');

//      Route::resource('wallet', 'Admin\WalletController');

//        Route::post('api/contracts', 'Admin\APIController@cfontracts');

      Route::post('logout', function (){
        Auth::logout();
        return redirect('/');
      })->name('logout');
});
