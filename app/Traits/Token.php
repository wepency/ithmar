<?php

namespace App\Traits;

trait Token
{
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'user' => auth()->user(),
            'token_type' => 'bearer',
            'expires_in' => config('sanctum.expiration')
        ];
    }

    protected function respondAdminWithToken($token)
    {
        return [
            'access_token' => $token,
            'user' => auth('api_admin')->user(),
            'token_type' => 'bearer',
//            'expires_in' => JWTAuth::factory()->getTTl()
        ];
    }
}
