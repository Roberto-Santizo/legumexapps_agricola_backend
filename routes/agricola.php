<?php

use App\Http\Controllers\Agricola\CdpController;
use App\Http\Controllers\Agricola\CropController;
use App\Http\Controllers\Agricola\FincaController;
use App\Http\Controllers\Agricola\FincaGroupController;
use App\Http\Controllers\Agricola\LoteController;
use App\Http\Controllers\Agricola\RecipeController;
use App\Http\Controllers\Agricola\SupplyController;
use App\Http\Controllers\Agricola\TaskController;
use App\Http\Controllers\Agricola\WeeklyPlanController;
use App\Http\Controllers\Agricola\WeeklyPlanTaskController;
use App\Http\Controllers\WeeklyPlanTaskInsumoController;
use Illuminate\Support\Facades\Route;

//CRUDS
Route::middleware('jwt.auth')->group(function () {
    Route::apiResource('/fincas',                       FincaController::class);
    Route::apiResource('/tasks',                        TaskController::class);
    Route::apiResource('/lotes',                        LoteController::class);
    Route::apiResource('/crops',                        CropController::class);
    Route::apiResource('/recipes',                      RecipeController::class);
    Route::apiResource('/cdps',                         CdpController::class);
    Route::apiResource('/supplies',                     SupplyController::class);
    Route::apiResource('/finca-groups',                 FincaGroupController::class);
    Route::apiResource('/weekly-plans',                 WeeklyPlanController::class);
    Route::apiResource('/weekly-plan-tasks',            WeeklyPlanTaskController::class);
    Route::apiResource('/weekly-plan-task-supplies',    WeeklyPlanTaskInsumoController::class);
});

//FUNCTIONALITYS
Route::middleware('jwt.auth')->group(function () {
    Route::post('/tasks/uploadFile',                [TaskController::class, 'uploadFile']);
    Route::post('/weekly-plans/uploadTasks/{id}',   [WeeklyPlanController::class, 'uploadTasksToWeeklyPlan']);
});
