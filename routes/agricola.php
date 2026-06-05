<?php

use App\Http\Controllers\Agricola\FincaController;
use App\Http\Controllers\Agricola\TaskController;
use App\Http\Controllers\Agricola\LoteController;
use Illuminate\Support\Facades\Route;

//CRUDS
Route::middleware('jwt.auth')->group(function () {
    Route::apiResource('/fincas', FincaController::class);
    Route::apiResource('/tasks', TaskController::class);
    Route::apiResource('/lotes', LoteController::class);
});

//FUNCTIONALITYS
Route::middleware('jwt.auth')->group(function () {
    Route::post('/tasks/uploadFile', [TaskController::class, 'uploadFile']);
});
