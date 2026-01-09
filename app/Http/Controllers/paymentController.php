<?php

namespace App\Http\Controllers;

use App\Models\BookingInvestorInvoice;
use App\Models\Contract;
use App\Models\Later;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class paymentController extends Controller
{

    // private static string $terminalId = "allied";
    // private static string $password = "allied@URWAY_123";
    // private static string $merchant_key = "60a5eb61ee0590e0132550fd951346d35e27c7deb49d6967f68aa82395643c14"; // Will be provided by URWAY
    
    
    private static string $terminalId = "mabetW";
    private static string $password = "mabet@URWAY_979";
    private static string $merchant_key = "8947879cce387daf3f130722da44a4cf43c6cfab51a3f2c1a697cca9dbad205b"; // Will be provided by URWAY
    
    private static $currencycode = "SAR";

    public static function addCredit(Request $request){
        $request->validate([
            'credit' => 'required|numeric|min:500'
        ]);

        $idorder = rand(11111,99999);
        $amount = number_format($request->credit, 2, '.', '');
        $response_url = investor_url('credit/success');
        cache()->forget('credit-' . Auth::id());

        return self::payInfo($idorder, $amount, $response_url);
    }

    public static function pay($code) {
        $contract = Contract::where('code', $code)->first();
        $idorder = $contract->code; //Customer Order ID
        $amount = number_format($contract->total + $contract->services_total, 2, '.', '');
        $response_url = investor_url('contracts/save/success');

        return self::payInfo($idorder, $amount, $response_url);
    }

    public static function payInvoice(Request $request) {
        $code = $request->code;
        $invoice = BookingInvestorInvoice::findOrFail(base64_decode($code));
        $idorder = str_pad($invoice->id,6,'0',STR_PAD_LEFT);
        $amount = number_format((investor_to_pay($invoice) - $invoice->locked_paid), 2, '.', '');
        $response_url = investor_url('invoices/save/success');

        return self::payInfo($idorder, $amount, $response_url);
    }

    public static function payInfo($idorder, $amount, $response_url){

        $terminalId = self::$terminalId;
        $password = self::$password;
        $merchant_key = self::$merchant_key;
        $currencycode = self::$currencycode;

//        $ipp = self::get_server_ip();
        $ipp = '197.59.109.30';

        $txn_details= $idorder.'|'.$terminalId.'|'.$password.'|'.$merchant_key.'|'.$amount.'|'.$currencycode;
        $hash=hash('sha256', $txn_details);

        $fields = array(
            'trackid' => $idorder,
            'terminalId' => $terminalId,
            'customerEmail' => 'customer@email.com',
            'action' => "1",  // action is always 1
            'merchantIp' =>$ipp,
            'password'=> $password,
            'currency' => $currencycode,
            'country'=>"SA",
            'amount' => $amount,
            "udf1"              =>"Test1",
//            "udf2"              =>"https://urway.sa/urshop/scripts/response.php",//Response page URL
            "udf2"              => $response_url,//Response page URL
            "udf3"              =>"",
            "udf4"              =>"",
            "udf5"              =>"Test5",
            'requestHash' => $hash  //generated Hash
        );

//        return $fields;
        $data = json_encode($fields);
//        https://payments.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest
        $ch=curl_init('https://payments.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest'); // Will be provided by URWAY
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $server_output =curl_exec($ch);
        //close connection
        curl_close($ch);
        $result = json_decode($server_output);

        if (!empty($result->payid) && !empty($result->targetUrl)) {
            $url = $result->targetUrl . '?paymentid=' .  $result->payid;
            return redirect()->to($url);
//            header('Location: '. $url, true, 307);//Redirect to Payment Page
        }else{

//            print_r($result);
            echo "<br/><br/>";
//            print_r($data);
            die();
        }
    }

    private static function get_server_ip() {
//        $ipaddress = '';
        $ip = '';

//        if (getenv('HTTP_CLIENT_IP'))
//            $ipaddress = getenv('HTTP_CLIENT_IP');
//        else if(getenv('HTTP_X_FORWARDED_FOR'))
//            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
//        else if(getenv('HTTP_X_FORWARDED'))
//            $ipaddress = getenv('HTTP_X_FORWARDED');
//        else if(getenv('HTTP_FORWARDED_FOR'))
//            $ipaddress = getenv('HTTP_FORWARDED_FOR');
//        else if(getenv('HTTP_FORWARDED'))
//            $ipaddress = getenv('HTTP_FORWARDED');
//        else if(getenv('REMOTE_ADDR'))
//            $ipaddress = getenv('REMOTE_ADDR');
//        else
//            $ipaddress = '192.168.1.1';

//        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
//            $ip = $_SERVER['HTTP_CLIENT_IP'];
//        }
//        //whether ip is from the proxy
//        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//        }
//        //whether ip is from the remote address
//        else{
//            $ip = $_SERVER['REMOTE_ADDR'];
//        }
//
//        if ($ip == '')
//            $ip = '192.168.1.1';

        return '192.168.1.1';
//        return $ip;
    }

    private static function amount($number){
        return number_format($number, 2, '.', '');
    }

    public function creditSuccess(Request $request){
        $page_title = '';

        if ($this->checkStatusOfPayment($request)){
            Wallet::create([
                'credit' => $_GET['amount'],
                'user_id' => auth()->id(),
                'type' => 'investor_add'
            ]);

            return redirect()->to(investor_url('credit'))->with('success', 'تم إضافة الرصيد بنجاح.');
        }

        return redirect()->to(investor_url('credit'))->with('error', 'بيانات البطاقه خاطئة أو البطاقه لا تعمل ، برجاء التأكد من البطاقة.');
    }

    public function singleContractSuccess(Request $request){
        $page_title = '';

        if ($this->checkStatusOfPayment($request)){
            $trackid = $request->get('TrackId');
            $responseCode = $_GET['ResponseCode'];
            $amount = $_GET['amount'];

            $contracts = Contract::where('code', $trackid)->first();

            $contracts->payment_type = 'paid';
            $contracts->status = 1;

            if($contracts->save()){
                if ($contracts->reservation_id != ''){
                    Wallet::create([
                        'user_id' => $contracts->user_id,
                        'credit' => 20,
                        'type' => 'cashback'
                    ]);
                }
            }

           return view('MyFatoorah.success', compact('contracts', 'page_title'));
        }

        return view('MyFatoorah.fail', compact('page_title'));
    }

    public function singleInvoiceSuccess(Request $request){
        $page_title = '';

        if ($this->checkStatusOfPayment($request)){
            $trackid = (int) $request->get('TrackId');
//            $responseCode = $_GET['ResponseCode'];
//            $amount = $_GET['amount'];

            $invoice = BookingInvestorInvoice::findOrFail($trackid);

            $invoice->status = 1;
            $invoice->save();

            $to_pay = investor_to_pay($invoice) - $invoice->locked_paid;

            @create_booking_history_record($invoice->id, 'BookingInvestorInvoice', 'paid', $to_pay);

            return redirect()->to('invoices')->with('success', 'تم الدفع بنجاح.');
        }

        return redirect()->to('invoices')->with('error', '');
    }

    private function checkStatusOfPayment($request){
        $terminalId = self::$terminalId;
        $password = self::$password;
        $key = self::$merchant_key;

        $requestHash = "" . $request->get('TranId') . "|" . $key . "|" . $_GET['ResponseCode'] . "|" . $_GET['amount'] . "";
        $txn_details1 = "" . $_GET['TrackId'] . "|" . $terminalId . "|" . $password . "|" . $key . "|" . $_GET['amount'] . "|SAR";

        $hash = hash('sha256', $requestHash);

        if ($hash === $_GET['responseHash']) {

            $txn_details1 = "" . $_GET['TrackId'] . "|" . $terminalId . "|" . $password . "|" . $key . "|" . $_GET['amount'] . "|SAR";
            //Secure check
            $requestHash1 = hash('sha256', $txn_details1);
            $apifields    = array(
                'trackid' => $_GET['TrackId'],
                'terminalId' => $terminalId,
                'action' => '10',
                'merchantIp' => "",
                'password' => $password,
                'currency' => "SAR",
                'transid' => $_GET['TranId'],
                'amount' => $_GET['amount'],
                'udf5' => "",
                'udf3' => "",
                'udf4' => "",
                'udf1' => "",
                'udf2' => "",
                'requestHash' => $requestHash1
            );

            $apifields_string = json_encode($apifields);

            $url = "https://payments.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest";
            $ch  = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $apifields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($apifields_string)
            ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

            //execute post
            $apiresult = curl_exec($ch);
            // print_r($apiresult);die;
            $urldecodeapi        = (json_decode($apiresult, true));
            $inquiryResponsecode = $urldecodeapi['responseCode'];
            $inquirystatus       = $urldecodeapi['result'];

            if ($_GET['Result'] === 'Successful'  && $_GET['ResponseCode'] === '000') {

                if($inquirystatus=='Successful' || $inquiryResponsecode=='000'){
//                    echo "Success";
//                    $trackid = $request->get('TrackId');
//                    $responseCode = $_GET['ResponseCode'];
//                    $amount = $_GET['amount'];

//                    $contracts = Contract::where('code', $trackid)->first();

//                    $contracts->status = 1;
//                    $contracts->save();

                    return true;

                }else {
                    return false;
                }

            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function success($request, $view, $errorView = ''){
        $terminalId = self::$terminalId;
        $password = self::$password;
        $key = self::$merchant_key;

        $requestHash = "" . $request->get('TranId') . "|" . $key . "|" . $_GET['ResponseCode'] . "|" . $_GET['amount'] . "";
        $txn_details1 = "" . $_GET['TrackId'] . "|" . $terminalId . "|" . $password . "|" . $key . "|" . $_GET['amount'] . "|SAR";


        $hash = hash('sha256', $requestHash);

        if ($hash === $_GET['responseHash']) {

            $txn_details1 = "" . $_GET['TrackId'] . "|" . $terminalId . "|" . $password . "|" . $key . "|" . $_GET['amount'] . "|SAR";
            //Secure check
            $requestHash1 = hash('sha256', $txn_details1);
            $apifields    = array(
                'trackid' => $_GET['TrackId'],
                'terminalId' => $terminalId,
                'action' => '10',
                'merchantIp' => "",
                'password' => $password,
                'currency' => "SAR",
                'transid' => $_GET['TranId'],
                'amount' => $_GET['amount'],
                'udf5' => "",
                'udf3' => "",
                'udf4' => "",
                'udf1' => "",
                'udf2' => "",
                'requestHash' => $requestHash1
            );

            $apifields_string = json_encode($apifields);

            $url = "https://payments.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest";
            $ch  = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $apifields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($apifields_string)
            ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

            //execute post
            $apiresult = curl_exec($ch);
            // print_r($apiresult);die;
            $urldecodeapi        = (json_decode($apiresult, true));
            $inquiryResponsecode = $urldecodeapi['responseCode'];
            $inquirystatus       = $urldecodeapi['result'];

            if ($_GET['Result'] === 'Successful'  && $_GET['ResponseCode'] === '000') {

                if($inquirystatus=='Successful' || $inquiryResponsecode=='000'){
//                    echo "Success";
//                    $trackid = $request->get('TrackId');
//                    $responseCode = $_GET['ResponseCode'];
//                    $amount = $_GET['amount'];

//                    $contracts = Contract::where('code', $trackid)->first();

//                    $contracts->status = 1;
//                    $contracts->save();

                  return $view;

                }else {
                    echo "Something went wrong!!! Secure Check failed!!!!!!!";
                }

            } else {
                return $errorView;
            }
        } else {
            echo "Hash Mismatch!!!!!!!";
        }
    }

    public function payLater(Request $request){
        $page_title = 'دفع الاواجل';

        $pay_contracts = explode(',' , $request->pay_later_contract);

        $contracts = Contract::whereIn('id', $pay_contracts);

        $sum = $contracts->sum('total', 'services_total');
//        $sum = $contracts->sum(\DB::raw('total + services_total'));
        $contracts = $contracts->get();

//        return $sum;
        $later = Later::create([
            'contracts' => serialize($pay_contracts),
            'total' => number_format($sum, 2)
        ]);

        return view('contracts.pay_later', compact('contracts', 'sum', 'page_title', 'pay_contracts', 'later'));
    }

    public function payLaterGateway(Request $request, $investor, $id){
        $decode = @base64_decode($request->c1o2n3t4) ?? '';
        $contracts_array = explode(',', $decode);
        $contracts = Contract::whereIn('id', $contracts_array)->sum(\DB::raw('total + services_total'));

        $idorder = $id; //Customer Order ID
        $amount = number_format($contracts, 2, '.', '');
        $response_url = investor_url('contracts/save/laterSuccess');

        return self::payInfo($idorder, $amount, $response_url);
    }

    public function laterSuccess(Request $request)
    {
        if ($this->checkStatusOfPayment($request)){
            $trackid = $request->get('TrackId');
            $responseCode = $_GET['ResponseCode'];
            $amount = $_GET['amount'];

            $total = Later::findOrFail($trackid);
            $contracts = Contract::whereIn('id', unserialize($total->contracts))->get();

            foreach ($contracts as $contract) {
                $contract->payment_type = 'paid';
                $contract->save();
            }

            return redirect()->to(investor_url('contracts'))->with('success', 'تمت عملية دفع العقود الآجلة بنجاح.');
        }

        return redirect()->to(investor_url('contracts'))->with('error', 'هناك مشكلة في بيانات البطاقه ، برجاء التأكد من البيانات و إعادة المحاولة.');

//        return $this->success($request, $view);
    }
}
