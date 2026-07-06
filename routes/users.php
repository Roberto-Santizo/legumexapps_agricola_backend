<?php

use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\UserPermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt.auth')->group(function () {
    Route::apiResource('/users',                        UserController::class);
    Route::apiResource('/user-permissions',             UserPermissionController::class);


    Route::get('/users/getUserPermissions/{username}',  [UserController::class, 'getUserPermissions']);
});
