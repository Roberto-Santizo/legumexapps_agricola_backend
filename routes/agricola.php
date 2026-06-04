<?php

use App\Http\Controllers\Agricola\FincaController;
use App\Http\Controllers\Agricola\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/fincas', FincaController::class)->middleware('jwt.auth');
Route::apiResource('/tasks', TaskController::class)->middleware('jwt.auth');