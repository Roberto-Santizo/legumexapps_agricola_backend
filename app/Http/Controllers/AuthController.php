<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Interfaces\Auth\AuthServiceInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthServiceInterface $authService)
    {
        try {
            $data = $request->validated();
            $token = $authService->login($data);

            return response()->json([
                'statusCode' => 200,
                'message' => 'Sesión Iniciada Correctamente',
                'token' => $token,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => $th->getCode(),
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function checkstatus()
    {
        try {
            $payload = JWTAuth::getPayload();

            return response()->json([
                'statusCode' => 200,
                'message' => 'Token válido',
                'data' => [
                    'id' => $payload->get('id'),
                    'name' => $payload->get('name'),
                    'role' => $payload->get('role'),
                    'username' => $payload->get('username'),
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => $th->getCode(),
                'message' => $th->getMessage(),
            ]);
        }
    }
}
