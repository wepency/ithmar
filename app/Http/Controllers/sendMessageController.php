<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sendMessageController extends Controller
{
    public function sendrr(){
        $url = 'https://api.yamamah.com/SendSMS';

        $fields = array(
            "Username" => "966533943775",
            "Password" => "seD40925",
            "Message" => "كود التحقق الخاص بك لإصدار العقد : 885887",
            "RecepientNumber" =>"0533943775",
            "ReplacementList" =>"",
            "SendDateTime" => "0",
            "EnableDR" =>False,
            "Tagname"=>"TOPAPP",
            "VariableList"=>"0"
        );

        $fields_string=json_encode($fields);

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
        echo $result;
        //close connection
        curl_close($ch);
    }


    public function send(){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.msegat.com/gw/sendsms.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        $fields = array(
            "userName" => "lafontainehotels",
            "numbers" => "966533943775",
            "userSender" => "ethmaar",
            "apiKey" => "86ceb9981f6506ef7dbca326fa0850e9",
            "msg" => "Testing"
        );

        $fields_string=json_encode($fields);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        var_dump($info["http_code"]);
        var_dump($response);
    }
}
