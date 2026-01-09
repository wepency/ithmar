<?php

use App\Models\BookingUnit;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('send_message/{id}', function ($id){
//    $user = \App\Models\User::findOrFail($id);
//    $tokens = [$user->fcm_token];
//
//    push_single_notification($tokens, 'testing', 'testing body');
//});

// Pages
Route::view('/privacy', 'privacy');

Route::get('event', function (){
    $data['notification'] = 'هناك انشاء عقد جديد';
    @push_realtime_notification('contract-created-channel', 'App\\Events\\ContractCreated', $data);
});

Route::get('contract/{code}/verifyPhone', 'contractsController@verify')
    ->where('code', '(.*)');

Route::get('contract/{code}/pay-validate', 'contractsController@payValidate')
    ->where('code', '(.*)');

Route::get('/', 'HomeController@index');

Route::get('credit_check', function (){
    return \App\Models\User::find(156)->credit()->sum('credit');
});

Route::get('test', function (){
    dd(BookingUnit::where('user_id', auth()->id())->active()->paginate(10));
});

// Upload Invoice Form
Route::get('reservation/{code}/uploadRefund', 'Reservations\onlineReservationsController@uploadInvestorInvoiceForm')->name('upload.investor.refund.form');
Route::post('reservation/{code}/uploadRefund', 'Reservations\onlineReservationsController@postInvestorInvoice')->name('put.investor.refund');
Route::get('reservation/{code}', 'Reservations\onlineReservationsController@uploadInvoice')->name('upload.invoice');
Route::put('reservation/{code}', 'Reservations\onlineReservationsController@postInvoice')->name('upload.post.invoice');

Route::get('/ip', function (){
//    $arr_ip = geoip()->getLocation('154.182.111.64');
//    return $arr_ip->city;
//    print_r($arr_ip);


//    $agent = new \Jenssegers\Agent\Agent;

//    return $agent->deviceType();
});

//Route::get('code', function (){
//   return get_code();
//});

//Route::get('/firebase', function(){
//    return view('firebase');
//});
//
//Route::post('/save-token', function (\Illuminate\Http\Request $request){
//    \App\Models\User::find(\auth()->id())->update(['remember_token', $request->token]);
//})->name('save-token');

//Route::post('/save-firebase', function (\Illuminate\Http\Request $request){
//
//    $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
//
//    $SERVER_API_KEY = 'AAAAyzXj8Ts:APA91bH4tymiYFKKZsCvAAMThBRSHmZcVGWdyHbLVndmCoq5KeGSGvQL73yot32D3gLML2MtszTh1okBDdSj21z70qRWTwqyBSzjVPmSx7WYx508UvX3FToT0KZI34kmC8fQfViwGih4';
//
//    $data = [
//        "registration_ids" => $firebaseToken,
//
//        "notification" => [
//            "title" => $request->title,
//            "body" => $request->body,
//            "content_available" => true,
//            "priority" => "high",
//        ]
//    ];
//
//    $dataString = json_encode($data);
//
//    $headers = [
//        'Authorization: key=' . $SERVER_API_KEY,
//        'Content-Type: application/json',
//    ];
//
//    $ch = curl_init();
//
//    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//    curl_setopt($ch, CURLOPT_POST, true);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
//
//    $response = curl_exec($ch);
//});

Route::post('contracts/save', 'contractsController@save');
Route::get('contract/check/{code}', 'contractsController@check');

Route::get('/request', 'requestController@getRequest');
Route::post('/request', 'requestController@postRequest');

Route::get('contract/{code}/draft-show', 'ContractDraftController@draft')
    ->where('code', '(.*)');

Route::get('contract/draft/{code}/{token}', 'ContractDraftController@showDraft');
Route::get('bonds/{code}', 'BondsController@show')->name('bond.show');


//Route::get('contract/{code}/verifyCode', 'contractsController@verifyCode');
// Validate 2 factor auth view
Route::view('validate', 'auth.two_factor');
// User Two Factor
Route::post('validate-code', 'TwoFactorController@validateCode')->name('factor.validate');
Route::post('validate/resend', 'TwoFactorController@resend')->name('factor.validate.resend');

Route::middleware(['auth', 'FrontEnd'])->group(function () {

    // Reservations Website Forms and tables
    Route::put('gallery/profit/{unit_id}', 'Reservations\profitController@update')->name('profit.update');
    Route::resource('gallery', 'Reservations\galleryController')->except('show');

    // Availability
    Route::put('availability/{unit_id}/{availability}', 'Reservations\pricesController@update')->name('availability.update');
    Route::post('availability/{unit_id}/close', 'Reservations\pricesController@close')->name('availability.close');
    Route::post('availability/{unit_id}/open', 'Reservations\pricesController@open')->name('availability.open');
    Route::get('availability/{unit_id}/create', 'Reservations\pricesController@create')->name('availability.create');
    Route::get('availability/{waiting}/show-waiting', 'Reservations\pricesController@waiting')->name('availability.waiting');
    Route::post('availability/{unit_id}', 'Reservations\pricesController@store')->name('availability.store');
    Route::resource('availability', 'Reservations\pricesController')->except('create', 'store', 'edit', 'update');
    Route::get('availability/{unit_id}/{availability}/edit', 'Reservations\pricesController@edit')->name('availability.edit');
    Route::delete('availability/{unit_id}/{availability}', 'Reservations\pricesController@destroy')->name('availability.destroy');

    // Online Reservations
    Route::get('online-reservations', 'Reservations\onlineReservationsController@index')->name('online-reservation.index');
    Route::put('online-reservations/{reservation_id}/accept', 'Reservations\onlineReservationsController@acceptDownPayment')->name('online-reservation.acceptDownPayment');
    Route::put('online-reservations/{reservation_id}/acceptReservation', 'Reservations\onlineReservationsController@acceptReservation')->name('online-reservation.acceptReservation');

    // Invoices
    Route::get('invoices/html/{invoice_id}', 'Reservations\invoicesController@render')->name('invoices.html');
    Route::resource('invoices', 'Reservations\invoicesController');

    // Old Site Routes
    Route::get('contracts', 'contractsController@getContracts');

    Route::post('contract/{code}/payment', 'paymentController@pay');
    Route::get('contracts/add', 'contractsController@add');

    Route::get('contract/{code}/edit', 'contractsController@edit');
    Route::put('contract/{code}/edit', 'contractsController@update');

    Route::put('contract/cancel/{code}', 'contractsController@cancelContract');
    Route::get('contract/{code}', 'contractsController@show');

    Route::get('request/success', 'requestController@success');
    Route::get('all-requests', 'requestController@all');

    Route::get('all-units', 'unitsController@all');

    Route::get('unit/update/{code}', 'unitsController@edit');
    Route::put('unit/update/{code}', 'unitsController@update');

    Route::get('contracts/save/success', 'paymentController@singleContractSuccess');
//    Route::get('contracts/save/failed', 'contractsController@failed');

    Route::post('image-temp-upload', 'imageController@upload');

    Route::post('pay/{code}', 'paymentController@pay');

    Route::get('user/data', 'userController@getUser');
    Route::put('user/update', 'userController@updateUser');

    // Bank Information
    Route::get('user/bank', 'BankInfoController@getBank');
    Route::put('user/bank/update', 'BankInfoController@updateBank')->name('user.banks.update');

    Route::get('services', 'servicesController@index');
    Route::post('services', 'servicesController@store');
    Route::delete('services/{service_id}', 'servicesController@destroy');

    Route::get('credit', 'creditController@index');
    Route::get('credit/history', 'creditController@history')->name('credit.history');
    Route::get('credit/withdraw', 'creditController@withdraw')->name('credit.withdraw');
    Route::post('credit/withdraw', 'creditController@withdraw')->name('credit.do.withdraw');
    Route::get('credit/success', 'paymentController@creditSuccess');
    Route::post('add-credit', 'paymentController@addCredit');
    Route::post('credit-request', 'creditController@requestCredit');
    Route::post('payByCredit', 'creditController@payByCredit');
    Route::post('payByCreditBulk', 'creditController@payByCreditBulk');

    // Investor Pay Invoice
    Route::post('payInvoiceByCredit', 'creditController@payInvoiceByCredit');
    Route::post('payInvoice', 'paymentController@payInvoice')->name('investor.pay.invoice');
    Route::get('invoices/save/success', 'paymentController@singleInvoiceSuccess')->name('investor.invoice.success');

    Route::post('pay_later', 'paymentController@payLater');
    Route::put('pay_later/{contract_id}', 'paymentController@payLaterGateway');
    Route::get('contracts/save/laterSuccess', 'paymentController@laterSuccess');

    // Update contract cars
    Route::post('contracts/{contract}/update-cars/{car?}', 'contractsController@updateCars');

    Route::post('api/getAvailableData', 'API\DatesController@getAvailableData');
    Route::post('api/upload-multiple', 'API\ImagesController@uploadMultiple');

    // Reservations
    // Generate Contract
    Route::get('reservation/contract/{id}', 'Reservations\onlineReservationsController@generateContract')->name('reservation.contract.generate');
    Route::put('reservation/contract/{id}', 'Reservations\ContractReservationController@save')->name('reservation.contract.store');

    // Video Upload
    Route::post('upload-video', 'VideoController@upload');
});

Route::get('contract/{code}/{token}', 'contractsController@getByToken')->name('contract.by.token');

Route::get('send_messages', 'sendMessageController@send');

Route::post('logout', function (){
    Auth::logout();
    return redirect('/');
})->name('front.logout')->middleware('auth');


//Route::get('/login', 'authController@loginForm')->name('login');
Route::post('/login', 'authController@login');

//Route::get('/register', 'authController@registerForm');

//Route::prefix('dashboard')->group(function (){
//    Route::middleware('admin')->group(function (){
//        // Sectors
//        Route::prefix('sector/{sector_id}')->group(function () {
//            Route::get('/', 'Sector\dashboardController');
//        });
//
//        // Investors
//        Route::prefix('investors/{investor_id}')->group(function () {
//            Route::get('/', 'Sector\dashboardController');
//        });
//
//        Route::get('/', 'Admin\DashboardController');
//    });
//
//    Route::get('/login', 'Admin\authController@loginForm');
//    Route::post('/login', 'Admin\authController@postForm');
//});

Route::prefix('myfatoorah')->group(function () {
    Route::get('/redirect', 'MyFatoorahController@redirect')->name('myfatoorah.redirect');
    Route::post('/process', 'MyFatoorahController@paymentProcess')->name('myfatoorah.process');
    Route::get('/success', 'MyFatoorahController@success')->name('myfatoorah.success');
    Route::get('/fail', 'MyFatoorahController@fail')->name('myfatoorah.fail');
});

Route::post('get-single-contract/{code}', 'contractsController@getSingleContract');
Route::post('get-settings', 'API\settingsController@get');

Route::get('terms', 'pagesController@terms');

Route::post('get-beaches-investor/{sector_id}', 'API\beachesController@getBeachesForInvestor');
Route::post('get-villas-investor/{beach_id}', 'API\unitsController@getVillasForInvestor');
//Route::post('get-villas-investor/{beach_id}', 'API\unitsController@getVillasForInvestor');
Route::post('get-single-beach/{beach_id}', 'API\unitsController@getSingleBeach');

// Booking Routes
Route::post('get-villas-for-booking/{beach_id}', 'API\BookingController@getVillas');

Route::get('password/reset', 'PasswordResetController@getForm');
Route::post('password/reset', 'PasswordResetController@postReset');
Route::get('reset-password/{token}', 'PasswordResetController@showResetPasswordForm')->name('reset.password.get');
Route::post('reset-password', 'PasswordResetController@submitResetPasswordForm')->name('reset.password.post');


// Investors Bonds
Route::get('bonds', 'BondsController@get');
Route::post('addBond', 'BondsController@addBond');
Route::delete('bonds/{id}/delete', 'BondsController@destroy');

/* App Payment Routes */
Route::get('payInvoice/{id}', 'Reservations\InvoicesController@pay')->name('invoice.pay');
Route::get('payInvoice/{id}/show', 'Reservations\InvoicesController@show')->name('invoice.show');

//Route::post('bond', '');
//Route::put('bonds/{id}/changeState', '');
//Route::put('bonds/changeState', '');

//Route::get('email', function (){

//    $mg = Mailgun::create('7698832daf4c5bd0dead0bb442e9424e-cac494aa-d8f79161', 'https://api.eu.mailgun.net'); // For EU servers

// Now, compose and send your message.
//    $mg->messages()->send('fpe-sa.com', [
//        'from'    => 'alert@fpe-sa.com',
//        'to'      => 'ahmedyasersalama@gmail.com',
//        'subject' => 'Reset Password Code',
//        'text'    => 'Your reset code is: 855968'
//    ]);

//    return view('mail.contract.request');
//    return \App\Models\User::find(23)->notify(new \App\Notifications\ContractNotifications(\App\Models\Contract::find(1445)));
//    \DB::reconnect();
//});
