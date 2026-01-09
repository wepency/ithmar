<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Notifications\contractNotification;
use Config;
use Redirect;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Contract;

class MyFatoorahController extends Controller
{

    protected $apiURL;

    protected $apiKey;

    public function __construct()
    {
        $this->apiURL = env('MYFATOORAH_URL');

        $this->apiKey = env('MYFATOORAH_TOKEN');
    }


    public function redirect(Request $request)
    {
        $contract_id = $request->contract_id;
        return view('MyFatoorah.standard-redirect')->with('contract_id' , $contract_id);
    }

    public function paymentProcess() {

        $prices = Setting::first();

//        $invoiceItems[] = [
//            'ItemName' => 'Villa 12',
//            'contractId'  => request()->input('contract_id')
//        ];

        $invoiceItems[] = [
            'ItemName'  => request()->input('contract_id'),
            'Quantity'  => '1', //Item's quantity
            'UnitPrice' => $prices->price_after_vat ?? 0,
            'weight'    => 0,
            'Width'     => 0,
            'Height'    => 0,
            'Depth'     => 0
        ];

        $InvoiceValue = $prices->price_after_vat ?? 0;

        $postFields = [
            'NotificationOption' => 'Lnk', //'SMS', 'EML', or 'ALL'
            'InvoiceValue'       => $InvoiceValue,
            'CurrencyIso'        =>  'SAR',
            'CustomerName'       => 'ahmed',
            'CallBackUrl'        => route('myfatoorah.success'),
            'ErrorUrl'           => route('myfatoorah.fail'),
            'invoiceItems'       => $invoiceItems
        ];

        $json = $this->callAPI("$this->apiURL/v2/SendPayment", $this->apiKey, $postFields);

        if($json->IsSuccess){
            return Redirect::to($json->Data->InvoiceURL);
        }else{
            return view('MyFatoorah.fail');
        }
    }

    public function success()
    {
//        $payment_info = [
//            'Key' => request()->input('Id'),
//            'KeyType' => 'PaymentId'
//        ];

//        $json = $this->callAPI("https://api.myfatoorah.com/v2/GetPaymentStatus", $this->apiKey, $payment_info);

//        dd($json->Data->InvoiceItems[0]->ItemName);

//        if(!isset($json->Data->InvoiceItems))
//            abort(404);

//        dd($json);
//        dd($json->InvoiceItems['ItemName']);

//        $contract_id = $json->Data->InvoiceItems[0]->ItemName;

        $contracts = Contract::findOrFail($contract_id);
        $contracts->status = 1;
        $contracts->is_pending = 0;
        $contracts->save();

        $users = User::where('role', 'admin')->get();
        $sector = @User::where('role_id', $contracts->sector_id)->first() ?? [];

        foreach ($users as $user){
            $user->notify(new contractNotification($contracts));
        }

        $sector->notify(new contractNotification($contracts));

        $page_title = 'تم الحجز بنجاح';

        return view('MyFatoorah.success', compact('contracts', 'page_title'));
    }

    public function fail()
    {
        return view('MyFatoorah.fail');
    }


    //------------------------------------------------------------------------------
    /*
     * Call API Endpoint Function
     */

    public function callAPI($endpointURL, $apiKey, $postFields = [], $requestType = 'POST') {

        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST  => $requestType,
            CURLOPT_POSTFIELDS     => json_encode($postFields),
            CURLOPT_HTTPHEADER     => array("Authorization: Bearer $this->apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));

        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);

        curl_close($curl);

        if ($curlErr) {
            //Curl is not working in your server
            die("Curl Error: $curlErr");
        }


        $error = $this->handleError($response);
        if ($error) {
            die("Error: $error");
        }

        return json_decode($response);
    }

    //------------------------------------------------------------------------------
    /*
     * Handle Endpoint Errors Function
     */

    public function handleError($response) {

        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }

        //Check for the errors
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');

            $error = implode(', ', array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }

        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }

        return $error;
    }


}
