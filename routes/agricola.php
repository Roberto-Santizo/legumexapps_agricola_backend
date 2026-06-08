<?php

use App\Http\Controllers\Agricola\CdpController;
use App\Http\Controllers\Agricola\CropController;
use App\Http\Controllers\Agricola\FincaController;
use App\Http\Controllers\Agricola\LoteController;
use App\Http\Controllers\Agricola\RecipeController;
use App\Http\Controllers\Agricola\TaskController;

use Illuminate\Support\Facades\Route;

//CRUDS
Route::middleware('jwt.auth')->group(function () {
    Route::apiResource('/fincas', FincaController::class);
    Route::apiResource('/tasks', TaskController::class);
    Route::apiResource('/lotes', LoteController::class);
    Route::apiResource('/crops', CropController::class);
    Route::apiResource('/recipes', RecipeController::class);
    Route::apiResource('/cdps', CdpController::class);
});

//FUNCTIONALITYS
Route::middleware('jwt.auth')->group(function () {
    Route::post('/tasks/uploadFile', [TaskController::class, 'uploadFile']);
});
