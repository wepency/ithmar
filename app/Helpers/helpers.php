<?php

use App\Models\BookingDate;
use App\Models\Contract;
use App\Models\Token;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Pusher\Pusher;

function admin_url($path = ''){
    return url('dashboard/'.$path);
}

function sector_url($path = ''){
    $id = auth()->user()->role_id;
    return url('dashboard/sector/'.$id.'/'.$path);
}

function investor_url($path = ''){
    return url($path);
}

function unit_status(Object $unit){

    if ($unit->status === 0){
        return "<span class='badge-status badge-warning'>بإنتظار الموافقة</span>";
    }elseif($unit->status === 1){
        if(unit_is_not_valid($unit)) {
            return "<span class='badge-status badge-warning'>منتهية الصلاحية</span>";
        }
        return "<span class='badge-status badge-success'>الوحدة فعالة</span>";
    }else{
        return "<span class='badge-status badge-danger'>تم الإلغاء</span>";
    }
}

function unit_is_not_valid($unit){
    return $unit->valid_to < Carbon::now() && $unit->type == 'investor';
}

function table_color_request($unit){
    if ($unit->status === 0){
        return "table-warning";
    }elseif($unit->status === 1){
        return "";
    }else{
        return "table-danger";
    }
}
function currency($price){
    return $price.' ر.س';
}
function currency_format($number){
    return currency(number_format($number, 2));
}
function qr_code_encode($contract){
    $output  = $contract->code;
    $output .= 'space';
    $output .= $contract->from;
    $output .= 'space';
    $output .= $contract->to;

    return base64_encode($output);
}

function qr_code_decode($code){
    $decoded = base64_decode($code);

    $attrs = explode('space', $decoded);

    return [
        'code' => @$attrs[0] ?? '',
        'from' => @$attrs[1] ?? '',
        'to' => @$attrs[2] ?? ''
    ];
}

function contractMix($contract){
    return base64_encode($contract->code.'to'.$contract->token);
}
function contractMixDecode($code){
    $code = base64_decode($code);
    $code = explode('to', $code);
    return $code[0];
}
function get_format(){
    return 'Y-m-d';
}

function vat_without_percent($before, $after){
    $vat_diff = $after - $before;
    return ($vat_diff / $before)*100;
}

function vat_percent($before, $after){
    $vat_diff = $after - $before;
    return ($vat_diff / $before)*100 . "%";
}

function total_amount($before, $after){
    return number_format($after - $before, 2);
}
function date_parser($date){
    return Carbon::parse($date.' 23:45:00')->format('Y-m-d H:i:s');
}

function compare_to_now($date, $operator = '>') {
    $now = Carbon::now();

    switch ($operator){
        case '>':
            return $date > $now;
            break;
        case '<':
            return $date < $now;
            break;

        case '=':
            return $date == $now;
            break;
    }
}


function is_admin(){
    if (auth()->check()) {
        return auth()->user()->role === 'admin';
    }
}

function is_sector_admin(){
    if (auth()->check()) {
        return auth()->user()->role === 'sector';
    }
}

function format_date($date){
    return Carbon::parse($date)->format('d / m / Y');
}

function is_blocked(){
    return false;
    return auth()->user()->blocked;
}

function block_note(){
    return auth()->user()->blocked_note;
}

function sendSMSVAT($phone_number, $code){
    $url = 'https://api.yamamah.com/SendSMS';
    $settings = \App\Models\Setting::first();

    $fields = array(
        "Username" => "966533943775",
        "Password" => "seD40925",
        "Message" => $settings->confirmation.": {$code}",
        "RecepientNumber" => $phone_number,
        "ReplacementList" =>"",
        "SendDateTime" => "0",
        "EnableDR" =>False,
        "Tagname"=>"TOPAPP",
        "VariableList"=>"0"
    );

    $fields_string=json_encode($fields);

    //open connection
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => $fields_string
    ));


    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
}

function addPhoneValidateToken($phone_number){
    $code = rand(111111, 999999);

    $token = Token::create([
        'phonenumber' => $phone_number,
        'token' => $code
    ]);

    sendSMS($phone_number, $code);
}
function sendSMS($phone_number, $code) {
    $settings = \App\Models\Setting::first();

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://msegat.com/gw/sendsms.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);

    curl_setopt($ch, CURLOPT_POST, TRUE);

//    $fields = array(
//        "userName" => "Lafontainegourmet",
//        "numbers" => $phone_number,
//        "userSender" => "Astoria",
//        "apiKey" => "7337fab703604d159896799bb35e3e63",
////        "msg" => "كود التحقق الخاص بك لإصدار العقد : {$code}"
//        "msg" => $settings->confirmation.": {$code}"
//    );

    $phone = (int) $phone_number;

    $fields = array(
        // "userName" => "lafontainegourmet",
        "numbers" => $phone_number,
        // "userSender" => "elestez",
        // "apiKey" => "7337fab703604d159896799bb35e3e63",
        "userName" => "lafontainehotels",
        // "numbers" => $phone_number,
        "userSender" => "Ethmaar",
        "apiKey" => "86ceb9981f6506ef7dbca326fa0850e9",
//        "msg" => "كود التحقق الخاص بك لإصدار العقد : {$code}"
        "msg" => $settings->confirmation.": {$code}"
    );

    $fields_string=json_encode($fields);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json"
    ));

    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    return $response;
//    var_dump($info["http_code"]);
//    var_dump($response);
}

function sendSMSBody($phone_number, $body) {
//	$settings = \App\Models\Setting::first();

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://msegat.com/gw/sendsms.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);

    curl_setopt($ch, CURLOPT_POST, TRUE);
    
//     $fields = array(
//         "userName" => "lafontainehotels",
//         "numbers" => $phone_number,
//         "userSender" => "ethmaar",
//         "apiKey" => "86ceb9981f6506ef7dbca326fa0850e9",
//         "msg" => $body
// //		"msg" => $settings->confirmation.": {$code}"
//     );
    
    // $fields = array(
    //     "userName" => "lafontainegourmet",
    //     "numbers" => $phone_number,
    //     "userSender" => "Astoria",
    //     "apiKey" => "7337fab703604d159896799bb35e3e63",
    //     "msg" => $body
    // );
    
    $fields = array(
        // "userName" => "lafontainegourmet",
        // "numbers" => $phone_number,
        // "userSender" => "elestez",
        // "apiKey" => "7337fab703604d159896799bb35e3e63",
        
        "userName" => "lafontainehotels",
        "numbers" => $phone_number,
        "userSender" => "Ethmaar",
        "apiKey" => "86ceb9981f6506ef7dbca326fa0850e9",
        "msg" => $body
    );

    $fields_string=json_encode($fields);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json"
    ));

    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

//	var_dump($info["http_code"]);
//	var_dump($response);

//	return $response;

    return true;
}

function pad_code($code){
    return str_pad($code,6,'0',STR_PAD_LEFT);
}

function checkAvailability($from, $to, $unit, $object = []){
    $from_plus = Carbon::parse($from)->addDay()->format('Y-m-d H:i:s');
    $to_sub = Carbon::parse($to)->subDay()->format('Y-m-d H:i:s');

//        return $from_plus;
    $contract = Contract::where(function ($q) use ($from, $to, $to_sub, $from_plus){
        $q->whereBetween('from', [$from, $to_sub])
            ->orWhereBetween('to', [$from_plus, $to])
            ->orWhere(function ($query) use ($from, $to){
                $query->where('from', '<=', $from)
                    ->where('to', '>=', $to)->get();
            })
            ->get();
    })->whereNull('is_cancelled')->where('unit_id', $unit)->first();

//    $booking_unit = \App\Models\BookingUnit::where('unit_id', $unit)->first();
//    $available = null;
//
//    if (!is_null($booking_unit)){
//        $start_date = Carbon::parse($from)->format('Y-m-d H:i:s');
//        $end_date = Carbon::parse($to)->format('Y-m-d H:i:s');
//
//        $available = booking_availability($booking_unit->id, $start_date, $end_date);
//    }

    if (!empty($object)){
        return is_null($contract) || $object->id == $contract->id;
    }

    return is_null($contract);
}


function booking_availability($unit_id, $start_date, $end_date){
    return BookingDate::where('unit_id', $unit_id)->where(function (Builder $builder) use ($start_date, $end_date){
        $builder->whereBetween('from', [$start_date, $end_date])
            ->orWhereBetween('to', [$start_date, $end_date]);
    })->where(function (Builder $query){
        $query->whereNotIn('type', ['refused', 'waiting'])->orWhere(function (Builder $qu){
            $qu->where('type', 'waiting')->where('created_at', '>', Carbon::now()->subHours(5)->toDateTimeString());
        });
    })->limit(1)->get();
}

function search_contract($type = ''){
    return admin_url('contract?sector='.request('sector').'&beach='.request('beach').'&unit='.request('unit').'&from='.request('from').'&to='.request('to').'&type='.$type);
}


//function get_contract_status($contract){
//    if (!is_valid($contract)) {
//        return "انتهي";
//    }elseif(is_cancelled($contract)){
//        return "ملغي";
//    }else{
//        return "فعال";
//    }
//}

function get_contract_status($contract){

    $output = '';

    // 'phone','accepted','paid','unpaid','pay_later','exempt','rejected'
    switch ($contract->payment_type){
        case 'phone':
        case 'accepted':
        case 'unpaid':
            $output = "<span class='text text-warning'>غير مدفوع</span>";
            break;
        case 'pay_later':
            $output = "<span class='text text-primary'>آجل</span>";
            break;
        case 'exempt':
            $output = "<span class='text text-warning' style='color: #d35400 !important;'>معفي</span>";
            break;
        case 'paid':
            $output = "<span class='text text-success'>مدفوع</span>";
            break;
    }

    if($contract->status == 1 && $contract->payment_type == 'rejected') {
        $output = "<span class='text text-success'>مدفوع</span>";
    }

    return $output;
}

function get_contract_badge($contract){

    if($contract->payment_type == 'phone') {
        return "<span class='text text-warning'>بإنتظار تفعيل رقم الهاتف</span>";
    }

    if($contract->is_cancelled){
        return "<span class='text text-danger'>ملغي</span>";
    }

    if (!is_valid($contract)){
        return "<span class='text text-danger'>منتهي</span>";
    }

    if ($contract->is_accepted && $contract->payment_type == 'unpaid'){
        return "<span class='text text-warning'>بانتظار الدفع</span>";
    }

    if (!$contract->is_accepted){
        return "<span class='text text-warning'>بانتظار الموافقة</span>";
    }

    return "<span class='text text-success'>فعال</span>";
}

function get_contract_status_badge($contract){

    if($contract->payment_type == 'phone') {
        return "<span class='badge-status badge-warning badge-floating'>بإنتظار تفعيل رقم الهاتف</span>";
    }

    if($contract->is_cancelled){
        return "<span class='badge-status badge-danger badge-floating'>العقد ملغي</span>";
    }

    if (!is_valid($contract)){
        return "<span class='badge-status badge-danger badge-floating'>العقد منتهي</span>";
    }

    if ($contract->is_accepted && $contract->payment_type == 'unpaid'){
        return "<span class='badge-status badge-warning badge-floating'>بانتظار الدفع</span>";
    }

    if (!$contract->is_accepted){
        return "<span class='badge-status badge-warning badge-floating'>بانتظار الموافقة</span>";
    }

    return "<span class='badge-status badge-success badge-floating'>العقد فعال</span>";
}

function get_contract_table($contract){

    // 'phone','accepted','paid','unpaid','pay_later','exempt','rejected'
    $danger = ['rejected'];
    $warning = ['phone', 'unpaid', 'rejected'];

    if (!is_valid($contract) || in_array($contract->payment_type, $danger) || $contract->is_cancelled){
        return "table-danger";
    }

    if(in_array($contract->payment_type, $warning) || !$contract->is_accepted){
        return "table-warning";
    }

    return '';
}

function is_valid($contract){
    return Carbon::parse($contract->to) > Carbon::now();
}

function payment_credit(){
    return view('layouts.confirm-credit');
}

function acceptedNotCancelled($accepted, $cancelled){
    return $accepted && !$cancelled;
}

function get_code(){
    $code = date('ymd').rand(11111,99999);

    if (Contract::where('code', $code)->count() > 0){
        return get_code();
    }

    return $code;
}

function checkUserContractCode($contract){
    return auth()->check() && auth()->id() == $contract->user_id;
}

function ContractExists($contract){
    return !is_null($contract);
}

function getDraftLink($contract){
    return url('contract/draft/'.$contract->code.'/'.$contract->token);
}

function verifyPhone($contract){
    return url('contract/draft/'.$contract->code.'/'.$contract->token);
}

function getPhoneNumber($phonenumber){
    return '+966'.$phonenumber;
}
function num_to_chars($number){
    $numbers_array = str_split($number);
    $numbers_array_reverse = array_reverse($numbers_array);
    $output = [];

    $change = [
        0 => [
            0 => '',
            1 => 'واحد',
            2 => 'اثنين',
            3 => 'ثلاثة',
            4 => 'أربعة',
            5 => 'خمسة',
            6 => 'ستة',
            7 => 'سبعة',
            8 => 'ثمانية',
            9 => 'تسعة'
        ],
        1 => [
            0 => '',
            1 => 'عشر',
            2 => 'عشرون',
            3 => 'ثلاثون',
            4 => 'أربعون',
            5 => 'خمسون',
            6 => 'ستون',
            7 => 'سبعون',
            8 => 'ثمانون',
            9 => 'تسعون'
        ],
        2 => [
            0 => '',
            1 => 'مائة',
            2 => 'مائتان',
            3 => 'ثلاثمائة',
            4 => 'أربعمائة',
            5 => 'خمسمائة',
            6 => 'ستمائة',
            7 => 'سبعمائة',
            8 => 'ثمانمائة',
            9 => 'تسعمائة'
        ],
        3 => [
            0 => '',
            1 => 'ألف',
            2 => 'ألفان',
            3 => 'ثلاثة',
            4 => 'أربعة',
            5 => 'خمسة',
            6 => 'ستة',
            7 => 'سبعة',
            8 => 'ثمانية',
            9 => 'تسعة'
        ],
        4 => [
            0 => '',
            1 => 'ألف',
            2 => 'عشرون ألفاََ',
            3 => 'ثلاثون ألفاََ',
            4 => 'أربعون ألفاََ',
            5 => 'خمسون ألفاََ',
            6 => 'ستون ألفاََ',
            7 => 'سبعون ألفاََ',
            8 => 'ثمانون ألفاََ',
            9 => 'تسعون ألفاََ'
        ],
        5 => [
            0 => '',
            1 => 'مائة',
            2 => 'مائتا',
            3 => 'ثلاثمائة',
            4 => 'أربعمائة',
            5 => 'خمسمائة',
            6 => 'ستمائة',
            7 => 'سبعمائة',
            8 => 'ثمانمائة',
            9 => 'تسعمائة'
        ],
        6 => [
            0 => '',
            1 => 'مليون',
            2 => 'مليوني',
            3 => 'ثلاث ملايين',
            4 => 'أربعة ملايين',
            5 => 'خمسة ملايين',
            6 => 'ست ملايين',
            7 => 'سبعة ملايين',
            8 => 'ثمان ملايين',
            9 => 'تسع ملايين'
        ]
    ];

    $extra = [
        11 => 'أحد عشر',
        12 => 'اثنا عشر',
        13 => 'ثلاثة عشر',
        14 => 'أربعة عشر',
        15 => 'خمسة عشر',
        16 => 'ستة عشر',
        17 => 'سبعة عشر',
        18 => 'ثمانية عشر',
        19 => 'تسعة عشر'
    ];

    foreach ($numbers_array_reverse as $key => $number){
        $output[]= $change[$key][$number];
    }

    if (count($numbers_array) > 1){
        if ($numbers_array_reverse[1] == 1){
            $output[0] = $extra[$numbers_array_reverse[1].''.$numbers_array_reverse[0]];
            unset($output[1]);
        }else{
            $swap = $output[0];
            $output[0] = $output[1];
            $output[1] = $swap;
        }
    }

    if (count($numbers_array) > 4){
        if ($numbers_array_reverse[4] == 1){
            $output[4] = $extra[$numbers_array_reverse[4].''.$numbers_array_reverse[3]].' ألفاََ';
            unset($output[3]);
        }else{
//            dd($output);
            $index = 4;
            $swap = $output[$index];
            $output[$index] = $output[$index-1];
            $output[$index-1] = $swap;
        }
    }

    if (count($numbers_array) == 4){
        $num = $numbers_array_reverse[3];
        $output[3] = $num > 2 ? $change[3][$num].' آلاف' : $change[3][$num];
    }

    $i = 1;
    $output_attr = [];
    $output = array_reverse($output);

    foreach ($output as $key => $attr){
        if ($attr != '')
            $output_attr[] = $attr;

        if (count($output) > $i && (isset($output[$key+1]) && $output[$key+1] != '')){
            $output_attr[] = ' و ';
        }

        $i++;
    }

    return implode(' ', $output_attr);
}

function get_title($date){
    $title = '';

    switch ($date->type){
        case 'waiting':
            $title = 'بانتظار دفع العربون';
            break;
        case 'closed':
            $title = 'مغلق';
            break;
//        case 'pending':
//            $title = 'طور التأكيد ( اثمار )';
//            break;
        case 'approved':
            $title = 'محجوز';
            break;
    }

    return $title;
}
function get_color($date){
    $color = '';

    switch ($date->type){
        case 'waiting':
            $color = '#f1c40f';
            break;
        case 'pending':
            $color = '#2ecc71';
            break;
        case 'closed':
            $color = '#34495e';
            break;
        case 'approved':
            $color = '#e74c3c';
            break;
    }

    return $color;
}

function check_available($unit_id, $from, $to){
    return is_null(\App\Models\BookingAvailability::where('unit_id', $unit_id)->where(function (\Illuminate\Database\Eloquent\Builder $builder) use ($from,$to){
        $builder->where(function ($q) use($from){
            $q->where('from', '<=', $from)->where('to', '>', $from);
        })->orWhere(function ($q) use($to){
            $q->where('from', '<=', $to)->where('to', '>', $to);
        });
    })->first());
}

function check_available_except($unit_id, $book_id, $from, $to){
    return is_null(\App\Models\BookingAvailability::where('unit_id', $unit_id)->where(function (\Illuminate\Database\Eloquent\Builder $builder) use ($from,$to){
        $builder->where(function ($q) use($from){
            $q->where('from', '<=', $from)->where('to', '>', $from);
        })->orWhere(function ($q) use($to){
            $q->where('from', '<=', $to)->where('to', '>', $to);
        });
    })->where('id', '!=', $book_id)->first());
}
function number_with_zeros($num){
    return str_pad($num,6,'0',STR_PAD_LEFT);
}

function get_vertime(){
    return cache()->get('vertime');
}

function get_status($reservation){
    // waiting, pending, valid
    if(Carbon::now()->diffInMinutes($reservation->created_at) < get_vertime() && $reservation->status == ''){
        return 'waiting';
    }elseif($reservation->status == 1){
        return 'pending';
    }elseif($reservation->status == 2){
        // Waiting rest of total
        return 'downpayment';
    }elseif($reservation->status == 3){
        return 'waiting_final_approval';
    }elseif($reservation->status == 4){
        return 'valid';
    }elseif($reservation->status == 5){
        return 'cancelled';
    }else{
        return 'expired';
    }
}

function get_badge($reservation){
    switch (get_status($reservation)){
        case 'waiting':
            $output = "<span class='badge badge-status badge-floating badge-warning'>بانتظار الدفع</span>";
            break;
        case 'cancelled':
            $output = "<span class='badge badge-status badge-floating badge-danger'>ملغي</span>";
            break;
        case 'pending':
            $output = "<span class='badge badge-status badge-floating badge-info'>بانتظار تأكيد دفع العربون</span>";
            break;
        case 'downpayment':
            $output = "<span class='badge badge-status badge-floating badge-info'>بانتظار دفع باقي المبلغ</span>";
            break;
        case 'waiting_final_approval':
            $output = "<span class='badge badge-status badge-floating badge-info'>بانتظار التأكيد النهائي</span>";
            break;
        case 'valid':
            $output = "<span class='badge badge-status badge-floating badge-success'>مؤكد</span>";
            break;
        default:
            $output = "<span class='badge badge-status badge-floating badge-danger'>منتهي</span>";
            break;
    }

    return $output;
}

function deep_encode($id, $date){
    return base64_encode(base64_encode($id.'|'.$date));
}
function deep_decode($code){
    try {
        $decode = base64_decode(base64_decode($code));
        $split = explode('|', $decode);
        return $split[0] ?? 0;
    }catch (Exception $e){
        return abort(404);
    }
}

function is_factor_auth(){
    if (auth()->check() && auth()->user()->two_factor && !auth()->user()->factor_validated)
        return false;

    return true;
}

function push_realtime_notification($channel, $event, $data){
    $options = array(
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true
    );

    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
    );

    $pusher->trigger('contract-created-channel', 'App\\Events\\ContractCreated', $data);
}

// Client's Refunds Functions

function check_refund_status($row){
    // converted-reservation
    // waiting-approval

    if ($row->has_changed && $row->reservation_id){

        if ($row->refund){

            if ($row->refund->is_verified)
                return 'converted-reservation';

             return 'waiting-approval';
        }

        return 'refund-request';

    }elseif($row->old_reservation_id){

        return 'new-reservation';

    }

    return false;
}


function calculate_discount($amount, $row, $type = null){
    $type_col = is_null($type) ? 'discount_type' : 'type';
    $total_discount = 0;

    if ($row->{$type_col} == 'percent'){
        $total_discount = $amount - ((float) (($amount*$row->discount) / 100));
    }elseif ($amount > $row->dicount){
        $total_discount = $amount - $row->discount;
    }

    return number_format((float) $total_discount, 2);
}

function create_booking_history_record($hismodel_id, $model_name, $type, $extra = null, $user_type = 'App\Models\ResUser', $user_id = null) {
    $user_id = is_null($user_id) ? auth()->id() : $user_id;

    return \App\Models\BookingHistory::create([
        'hismodel_id' => $hismodel_id,
        'hismodel_type' => "App\Models\\$model_name",
        'type' => $type,
        'user_id' => $user_id,
        'user_type' => $user_type,
        'extra' => $extra
    ]);
}


function push_single_notification($tokens, $title, $body, $sound = 'default'){

    $url = 'https://fcm.googleapis.com/fcm/send';

//    $data = [
////        'body' => $body,
////        'title' => $title,
//        'vibrate' => 1,
//        'message' => true,
//        "to" => $tokens,
//        "notification" => [
//            "title" => $title,
//            "body" => $body,
//            "sound" => "alert.aiff",
////            "android_channel_id" => "high_importance_channel"
//        ],
//
//        "android" => [
//            "priority" => "high",
//            "notification" => [
//                "sound" => "alert.mp3"
//            ]
//        ]
//    ];

//    $fields = [
//        'registration_ids' => $tokens,
//        'data' => $data,
//        'priority' => 'high',
//    ];
//
//
//    return Http::withHeaders([
//        'Authorization: key='.env('SERVER_KEY'),
//        'Content-Type: application/json',
//    ])
//    ->post($url, $fields)
//    ->json();

//    return $response;
    $data = [
        "registration_ids" => $tokens,
//
        "notification" => [
            "title" => $title,
            "body" => $body,
            "sound" => "alert.aiff",
//            "android_channel_id" => "high_importance_channel"
        ],
//
        "android" => [
            "priority" => "high",
            "notification" => [
                "sound" => "alert.mp3"
            ]
        ]
    ];
//
    $dataString = json_encode($data);
//
    $headers = [
        'Authorization: key='.env('SERVER_KEY'),
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    return curl_exec($ch);
}

function numbers_api($number){
    return (float) number_format($number, 2, '.', '');
}


function client_message(){
    $output = "السلام عليكم ،،،";
    $output .= "شكراً اختيارك درة العروس";
    $output .= "
نتحدث اليكم بخصوص حجزكم لدينا
لقد تم تأكيد حوالتكم لحساباتنا
لكن مازلنا بحاجه للمعلومات التاليه :
لتسهيل اجراءات دخولكم

=== المعلومات الاساسية ===

اسم المستأجر ثلاثي :
رقم هوية المستأجر :
جنسية المسأجر :
اسم المرافقه ثلاثي ( انثى ) :
رقم هوية المرافقه :
جنسية المرافقه :
صلة القرابه :

=== السيارات المصرح لها  ===

١- نوع السياره :
رقم اللوحه ( حروف وارقام ) :

٢- نوع السياره :
رقم اللوحه ( حروف وارقام ) :

٣- نوع السياره :
رقم اللوحه ( حروف وارقام ) :

=== معلومات اخرى ===

* معلومه : لا يمكن ان يكون المستأجر الرئيسي ذكر و المرافق ذكر

* معلومه : يخضع عدد السيارات المصرح لها بالدخول لشروط حجزك

* معلومه : يمكنك متابعة حالة حجزك حتى اصدار العقد من خلال زر حجوزاتي :
https://ithmaar.sa/reservations

* معلومه : استمر بجمع النقاط للحصول على خصومات لاجازتك القادمة ( من خلال صفحة النقاط )
https://ithmaar.sa/points";

    return urlencode($output);
}

function investor_to_pay($invoice){
    return $invoice->profit+$invoice->tax+$invoice->violations;
}

function locked_credit(){
    return \App\Models\Wallet::where('user_id', auth()->id())->lockedCredit()->sum('credit');
}


function get_api_contract_status($contract){

    $data = [];

    // 'phone','accepted','paid','unpaid','pay_later','exempt','rejected'
    switch ($contract->payment_type){
        case 'phone':
        case 'accepted':
        case 'unpaid':
            $data['text'] = 'غير مدفوع';
            $data['bg_color'] = '#2ecc71';
            break;
        case 'pay_later':
            $data['text'] = 'آجل';
            $data['bg_color'] = '#f1c40f';
            break;
        case 'exempt':
            $data['text'] = 'معفي';
            $data['bg_color'] ='#f1c40f';
            break;
        case 'paid':
            $data['text'] = 'مدفوع';
            $data['bg_color'] = '#e74c3c';
            break;
    }



    if($contract->status == 1 && $contract->payment_type == 'rejected') {
        $data['text'] = 'مدفوع';
        $data['bg_color'] = '#e74c3c';
    }

    return $data;
}

function get_api_contract_status_badge($contract){

    if($contract->payment_type == 'phone') {
        return [
            'text' => 'بإنتظار تفعيل رقم الهاتف',
            'bg_color' => '#f1c40f'
        ];
    }

    if($contract->is_cancelled){
        return [
            'text' => 'العقد ملغي',
            'bg_color' => '#e74c3c'
        ];
    }

    if (!is_valid($contract)){
        return [
            'text' => 'العقد منتهي',
            'bg_color' => '#e74c3c'
        ];
    }

    if ($contract->is_accepted && $contract->payment_type == 'unpaid'){
        return [
            'text' => 'بانتظار الدفع',
            'bg_color' => '#f1c40f'
        ];
    }

    if (!$contract->is_accepted){

        return [
            'text' => 'بانتظار الموافقة',
            'bg_color' => '#f1c40f'
        ];
    }

    return [
        'text' => 'العقد فعال',
        'bg_color' => '##2ecc71'
    ];
}

function checkIfThursday() : bool{
    return (new Carbon())->dayOfWeek == Carbon::THURSDAY;
}


/**
 * Get the price and total of a contract based on the Unit, general price before and after.
 * If the Unit has a price, it will override the general price.
 * If the Unit's sector has a price, it will override the general price.
 * If neither the Unit nor the Unit's sector has a price, the general price will be used.
 *
 * @param Unit $unit
 * @param float $generalPriceBefore
 * @param float $generalPriceAfter
 * @return array
 */
function getContractPriceAndTotal(Unit $unit, $generalPriceBefore, $generalPriceAfter): array
{
    // Default values from settings
    $final_price = $generalPriceBefore;
    $final_total = $generalPriceAfter;
    $final_vat = vat_without_percent($generalPriceBefore, $generalPriceAfter);
    
    // Override with Unit prices if available
    if ($unit->price > 0) {
        $final_price = $unit->price;
        $final_vat = $unit->vat;
        $final_total = $unit->total;
    } 
    // Otherwise check Sector prices
    elseif ($unit->sector && $unit->sector->price > 0) {
        $final_price = $unit->sector->price;
        $final_vat = $unit->sector->vat;
        $final_total = $unit->sector->total;
    }

    return [
        'price' => $final_price,
        'total' => $final_total,
        'vat' => $final_vat
    ];
}