<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('get-beaches/{sector_id}', 'API\beachesController@getBeaches');
Route::post('get-villas/{beach_id}', 'API\unitsController@getVillas');

// Upload Unit Attachment
Route::get('get-attachments/{id}', 'API\attachmentsController@index');
Route::post('upload-attachment', 'API\attachmentsController@upload');
Route::post('download-attachment/{id}', 'API\attachmentsController@download');
Route::delete('remove-attachment/{id}', 'API\attachmentsController@delete');

Route::post('getBond', 'API\fundsController@getBond');
Route::post('getFunds', 'API\fundsController@get');

// Verify Phone Number
Route::post('send-sms-token/{phone_number}', 'API\validateContract@sms');
Route::post('confirm-validation', 'API\validateContract@validateCode');
Route::post('confirm-code-validation', 'API\validateContract@validateContract');

Route::post('checkAvailability', 'API\CheckAvailabilityController@check');

Route::post('getContractForBond', 'API\BondsController@getData');
