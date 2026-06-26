<?php

use App\Http\Controllers\Agricola\CdpController;
use App\Http\Controllers\Agricola\CropController;
use App\Http\Controllers\Agricola\CropInputController;
use App\Http\Controllers\Agricola\CropParameterController;
use App\Http\Controllers\Agricola\FincaController;
use App\Http\Controllers\Agricola\FincaGroupController;
use App\Http\Controllers\Agricola\LoteController;
use App\Http\Controllers\Agricola\RecipeController;
use App\Http\Controllers\Agricola\SupplyController;
use App\Http\Controllers\Agricola\TaskController;
use App\Http\Controllers\Agricola\WeeklyPlanController;
use App\Http\Controllers\Agricola\WeeklyPlanEmployeeController;
use App\Http\Controllers\Agricola\WeeklyPlanTaskController;
use App\Http\Controllers\Agricola\WeeklyPlanTaskEmployeeController;
use App\Http\Controllers\Agricola\WeeklyPlanTaskPartialClosureController;
use App\Http\Controllers\WeeklyPlanTaskInsumoController;
use Illuminate\Support\Facades\Route;

//CRUDS
Route::middleware('jwt.auth')->group(function () {
    Route::apiResource('/fincas',                                       FincaController::class);
    Route::apiResource('/tasks',                                        TaskController::class);
    Route::apiResource('/lotes',                                        LoteController::class);
    Route::apiResource('/crops',                                        CropController::class);
    Route::apiResource('/recipes',                                      RecipeController::class);
    Route::apiResource('/cdps',                                         CdpController::class);
    Route::apiResource('/supplies',                                     SupplyController::class);
    Route::apiResource('/finca-groups',                                 FincaGroupController::class);
    Route::apiResource('/weekly-plans',                                 WeeklyPlanController::class);
    Route::apiResource('/weekly-plan-tasks',                            WeeklyPlanTaskController::class);
    Route::apiResource('/weekly-plan-task-supplies',                    WeeklyPlanTaskInsumoController::class);
    Route::apiResource('/weekly-plan-employees',                        WeeklyPlanEmployeeController::class);
    Route::apiResource('/weekly-plan-task-employees',                   WeeklyPlanTaskEmployeeController::class);
    Route::apiResource('/weekly-plan-task-partial-closures',            WeeklyPlanTaskPartialClosureController::class);
    Route::apiResource('/crops-inputs',                                 CropInputController::class);
    Route::apiResource('/crops-parameters',                             CropParameterController::class);
});

//FUNCTIONALITYS
Route::middleware('jwt.auth')->group(function () {
    //TASKS
    Route::post('/tasks/uploadFile',                                                [TaskController::class, 'uploadFile']);

    //WEEKLY PLANS
    Route::post('/weekly-plans/uploadTasks/{id}',                                   [WeeklyPlanController::class, 'uploadTasksToWeeklyPlan']);

    //WEEKLY PLANS EMPLOYEE
    Route::post('/weekly-plan-employees/addEmployeesToFincaGroup/{id}',             [WeeklyPlanEmployeeController::class, 'addEmployeesToFincaGroup']);

    //FINCA GROUPS
    Route::get('/finca-groups/groupsSummaryByWeeklyPlan/{id}',                      [FincaGroupController::class, 'groupsSummaryByWeeklyPlan']);

    //WEEKLY PLAN TASKS
    Route::get('/weekly-plan-tasks/getTasksForCalendar/{weeklyPlanId}',             [WeeklyPlanTaskController::class, 'getWeeklyPlanTasksForCalendar']);
    Route::get('/weekly-plan-tasks/getTasksByLote/{weeklyPlanId}',                  [WeeklyPlanTaskController::class, 'getWeeklyPlanTasksGroupByCdp']);
    Route::get('/weekly-plan-tasks/getTasksByCdp/{weeklyPlanId}/{cdp}',             [WeeklyPlanTaskController::class, 'getWeeklyPlanTasksByCdp']);
    Route::post('/weekly-plan-tasks/startTask/{id}',                                [WeeklyPlanTaskController::class, 'startWeeklyPlanTask']);
    Route::post('/weekly-plan-tasks/closeTask/{id}',                                [WeeklyPlanTaskController::class, 'closeWeeklyPlanTask']);
    Route::post('/weekly-plan-tasks/cleanTask/{id}',                                [WeeklyPlanTaskController::class, 'cleanWeeklyPlanTask']);

    //PARTIAL CLOSURES
    Route::post('/weekly-plan-task-partial-closures/addOrUpdate',                   [WeeklyPlanTaskPartialClosureController::class, 'addOrUpdatePartialClosure']);
});
