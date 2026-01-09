<?php

namespace App\Traits;

trait Encrypt
{
    private $encrypt_method;
    private $secret_key;
    private $secret_iv;
    private $key;
    private $iv;

    public function __construct()
    {
        $this->encrypt_method = env("ENCRYPTED_METHOD");
        $this->secret_key = env("SECRET_KEY");
        $this->secret_iv = env("SECRET_IV");

        $this->key = substr(hash('sha256', $this->secret_key), 0, 32);

        $this->iv = substr(hash('sha256', $this->secret_iv), 0, 16);
    }

    public function encrypt($data){
//        return $data;
        $string = json_encode($data);

        return openssl_encrypt($string, $this->encrypt_method, $this->key, 0, $this->iv);
    }

    public function decrypt($encryptToken){
        try{
            $decryptToken = openssl_decrypt($encryptToken, $this->encrypt_method, $this->key, 0, $this->iv);
            return json_decode($decryptToken);

        }catch (\Exception $e){
            return response()->json([
                'message' => $this->encrypt($e->getMessage()),
                'errors' => [],
                'success' => false
            ], 401);
        }
    }
}
