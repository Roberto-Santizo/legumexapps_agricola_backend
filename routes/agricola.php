<?php

use App\Http\Controllers\Agricola\FincaController;
use App\Http\Controllers\Agricola\TaskController;
use Illuminate\Support\Facades\Route;

//CRUDS
Route::middleware('jwt.auth')->group(function () {
    Route::apiResource('/fincas', FincaController::class);
    Route::apiResource('/tasks', TaskController::class);
});

//FUNCTIONALITYS
Route::middleware('jwt.auth')->group(function () {
    Route::post('/tasks/uploadFile', [TaskController::class, 'uploadFile']);
});
