<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\AuthServiceInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    public function login(array $data)
    {
        $token = JWTAuth::attempt($data);

        if(!$token) {
            throw new \Exception('Credenciales inválidas', 401);
        }
        
        return $token;
    }
}