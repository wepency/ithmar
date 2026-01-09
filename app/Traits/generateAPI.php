<?php

namespace App\Traits;

trait generateAPI
{
    use Encrypt;

    public function success($data = null, $message = null){
//        return response()->json([
//            'data' => $this->encrypt($data),
//            'message' => $this->encrypt($message),
//            'success' => true
//        ]);

        if (is_array($data)){
            if (empty($data))
                $data = null;
        }

        if (is_array($message)){
            if (empty($message))
                $message = null;
        }

        return response()->json([
            'data' => $data,
            'message' => $message,
            'success' => true
        ]);
    }

    public function error($errors = null, $message = null, $code = 401, $blocked = false){
        if (is_array($errors)){
            if (empty($errors))
                $errors = null;
        }

        if ($message == '')
            $message = null;

        $errors = is_array($errors) || is_null($errors) || gettype($errors) == 'string' ? $errors : $errors->toArray();

        return response()->json([
            'errors' => $this->array_flatten($errors),
            'message' => $message,
            'is_blocked' => $blocked,
            'success' => false
        ], $code);
    }

    private function array_flatten($array) {
        $return = array();

        if (is_array($array)){
            foreach ($array as $key => $value) {
                if (is_array($value)){ $return = array_merge($return, $this->array_flatten($value));}
                else {$return[] = $value;}
            }
        }

        return empty($return) ? null : $return;
    }
}
