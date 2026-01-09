<?php

namespace App\Traits;

use App\Models\BookingInvoice;
use App\Models\Bookings;
use App\Models\BookingUser;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait Payment
{
    // private static string $terminalId = "allied";
    // private static string $password = "allied@URWAY_123";
    // private static string $merchant_key = "60a5eb61ee0590e0132550fd951346d35e27c7deb49d6967f68aa82395643c14"; // Will be provided by URWAY


    private static string $terminalId = "mabetW";
    private static string $password = "mabet@URWAY_979";
    private static string $merchant_key = "8947879cce387daf3f130722da44a4cf43c6cfab51a3f2c1a697cca9dbad205b"; // Will be provided by URWAY
    
    private static string $requestURL = "https://payments.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest";

    // Test Credentials
//    private static string $terminalId = "allied";
//    private static string $password = "allied@123";
//    private static string $merchant_key = "a58a07c508918c12d32372ef4c360657c4125ebc7efe61abe1db6462e5866347"; // Will be provided by URWAY


    private static string $currencycode = "SAR";

//    private static string $requestURL = "https://payments-dev.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest";

    use generateAPI;

    public function pay(Request $request, $booking_id)
    {
        $booking_id = base64_encode(base64_encode($booking_id));
        $booking = Bookings::findOrFail($request->booking_id);

        return $this->success([
            'apple_pay' => [
                'status' => true,
                'to_pay' => $booking->down_payment
            ],
            'credit_card' => [
                'status' => true,
                'url' => route('api.payment.urway', $booking_id)
            ]
        ]);
    }

    public function payByCard($booking_id){
        $booking = Bookings::findOrFail($booking_id);

        if ($booking->status < 4 || $booking->status == '') {
            $idorder = $booking->id;
            $amount = number_format($booking->down_payment, 2, '.', '');
            $response_url = route('payment.response');

            return self::payInfo($idorder, $amount, $response_url);
        }

        abort(404);
    }

    public static function payInfo($idorder, $amount, $response_url){

        $terminalId = self::$terminalId;
        $password = self::$password;
        $merchant_key = self::$merchant_key;
        $currencycode = self::$currencycode;

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

        $data = json_encode($fields);
        $ch=curl_init(self::$requestURL); // Will be provided by URWAY
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
            echo "<br/><br/>";
            die();
        }
    }

    public function response(Request $request){
        $trackid = $request->get('TrackId');
        $booking = Bookings::find($trackid);

        if ($this->checkStatus($request)){
            return DB::transaction(function () use ($trackid, $booking){
                if ($booking->status < 4 || $booking->status == '') {
                    $investor = $booking->unit->unit->user_id;

//                    $locked = ($booking->down_payment * $booking->unit->profit_percentage) / 100;

//                    $locked = (($booking->sub_total * $booking->unit->profit_percentage) / 100) - ($booking->sub_total - $booking->total);
//                    $locked_vat = ($locked*15) / 100;

                    $locked = get_locked_total($booking->sub_total, $booking->total, $booking->unit->profit_percentage);

                    // Calculate Wallet
                    // Locked Amount
                    Wallet::create([
                        'user_id' => $investor,
                        'credit' => number_format($locked, 2),
                        'type' => 'booking_downpayment_locked',
                        'model_id' => $trackid
                    ]);

                    // Withdrawable
                    Wallet::create([
                        'user_id' => $investor,
                        'credit' => number_format($booking->down_payment - $locked, 2),
                        'type' => 'booking_downpayment',
                        'model_id' => $trackid
                    ]);

                    $user = BookingUser::find($booking->booking_user_id);

                    if (!is_null($user)){
                        $user->update([
                            'amount_paid' => $user->amount_paid + $booking->total
                        ]);
                    }

                    $to   = Carbon::parse($booking->to);
                    $from = Carbon::parse($booking->from);

                    $diff = $to->diffInDays($from);

                    $booking_dates = $booking->dates;

                    foreach ($booking_dates as $booking_date){
                        $booking_date->update([
                            'type' => 'approved'
                        ]);
                    }

                    $booking->update([
                        'status' => 4,
                        'is_ready' => 1,
                        'method' => 'credit'
                    ]);

                    $down_payment_percentage = $booking->unit->unit->user->down_payment ?? 100;
                    $down_payment = ($down_payment_percentage * $booking->total) / 100;

                    BookingInvoice::create([
                        'booking_id' => $trackid,
                        'subtotal' => $booking->sub_total,
                        'total' => $booking->total,
                        'down_payment_percentage' => $down_payment_percentage,
                        'down_payment' => $down_payment,
                        'profit_percentage' => $booking->unit->profit_percentage,
                        'booking_profit' => (($booking->total * $booking->unit->profit_percentage) / 100)
                    ]);

                    $clientPhone = $user->phonenumber;
                    $clientMsg = 'تم اتمام الحجز رقم '.str_pad($booking->id, 6, '0', STR_PAD_LEFT).' على الوحدة رقم '.($booking->unit->unit->unit_number ?? '').' بشاطئ: '.($booking->unit->unit->beach->beach ?? '').' - تاريخ الدخول '.$from->format('Y-m-d').' لمدة '.$diff.' يوم.';
                    @sendSMSBody($clientPhone, $clientMsg);

                    $investorPhone = $booking->unit->unit->user->phonenumber;
                    @sendSMSBody($investorPhone, $clientMsg);

                    $managers = \App\Models\Admin::whereHas('roles', function ($q){
                        return $q->where('name', 'call-center')->orWhere('name', 'mdyr-aaam');
                    })->get();

                    foreach ($managers as $manager) {
                        @sendSMSBody($manager->phonenumber, $clientMsg);
                    }

                    return redirect()->to('/reservations?status=payment_successful');
                }

                return redirect()->to('/book/payment?status=payment_failed');
            });

        }else {
            return redirect()->to('/book/payment?status=payment_failed');
        }
    }

    private function checkStatus($request){
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

            $url = self::$requestURL;
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

    public function responseSuccess()
    {
        return 'Success';
    }

    public function responseFailed()
    {
        return 'Success';
    }

    public function paymentType(Request $request){
        $request->validate([
            'booking_id' => 'required',
            'payment_type' => 'required'
        ]);

//        apple_pay
//        credit_card
//        pay_by_transaction

        Bookings::findOrFail($request->booking_id)->update([
            'method' => $request->payment_type
        ]);

        return $this->success();
    }
}
