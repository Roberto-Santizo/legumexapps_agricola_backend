<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;
use App\Interfaces\Users\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request, UserServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $service->createUser($data);

            return response()->json([
                'statusCode' => 201,
                'message' => 'Usuario Creado Correctamente'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => $th->getCode(),
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
