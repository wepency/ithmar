<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('login', 'AuthController@login');
Route::get('settings/whatsapp', 'SettingsController@whatsapp');

Route::group(['middleware' => ['api_token', 'auth:sanctum']], function (){

    // Account
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

    Route::post('update-fcm', 'AuthController@fcm');

    Route::prefix('available')->group(function (){
        Route::get('sectors', 'AvailableController@sectors');
        Route::get('{sector_id}/beaches', 'AvailableController@beaches');
        Route::get('{beach_id}/units', 'AvailableController@units');
        Route::get('{beach_id}/unit-types', 'AvailableController@unitsType');
        Route::post('{unit_id}/dates', 'AvailableController@dates');
    });

    Route::get('contracts', 'ContractsController@index');
    Route::post('contracts/create', 'ContractsController@store');
    Route::delete('contracts/{contract_id}/cancel', 'ContractsController@destroy');
//    Route::resource('contracts', 'ContractsController');

    // Calendar
    Route::get('units', 'UnitsController@getUnits');
    Route::get('calendar/{unit_id}', 'CalendarController@getDates');

    Route::post('calendar/{unit_id}/close', 'CalendarController@close');
    Route::post('calendar/{unit_id}/open', 'CalendarController@open');
    Route::post('calendar/{unit_id}/update-price', 'CalendarController@update');

    Route::get('notifications', 'NotificationsController@getNotifications');
    Route::get('notifications-count', 'NotificationsController@count');

    // Investors Invoices
    Route::get('invoices', 'InvoicesController@index');
    Route::get('invoices/count', 'InvoicesController@count');
    Route::get('invoices/{invoice_id}', 'InvoicesController@single');

    // Online Reservation
    Route::get('reservations', 'ReservationsController@index')->name('reservations');
    Route::post('reservations/{booking_id}/accept_downpayment', 'ReservationsController@acceptDownPayment')->name('api.accept_downpayment');
    Route::post('reservations/{booking_id}/accept_total', 'ReservationsController@acceptTotal')->name('api.accept_full_payment');
    Route::post('reservations/{booking_id}/upload_transaction', 'ReservationsController@uploadTransaction')->name('api.upload_transaction');

    // Credits & Wallets
    Route::get('wallet', 'WalletController@index');
    Route::get('wallet/withdraw/bank', 'WalletController@bank');
    Route::get('wallet/withdraw/history', 'WalletController@history');
    Route::post('wallet/add-balance', 'WalletController@addBalance');
    Route::post('wallet/withdraw', 'WalletController@withdrawRequest');

    // Payment
    Route::get('pay/card/urway/{id}', 'PaymentController@payByCard')
        ->where('id', '(.*)')
        ->name('api.payment.urway');

    Route::get('pay/card/response', 'PaymentController@response')->name('api.payment.response');
    Route::get('pay/card/response/credit-success', 'PaymentController@responseSuccess')->name('api.payment.response.success');
    Route::get('pay/card/response/credit-failed', 'PaymentController@responseFailed')->name('api.payment.response.failed');
});
