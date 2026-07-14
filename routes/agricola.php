<?php

use App\Http\Controllers\Agricola\CdpController;
use App\Http\Controllers\Agricola\CropController;
use App\Http\Controllers\Agricola\CropInputController;
use App\Http\Controllers\Agricola\CropParameterController;
use App\Http\Controllers\Agricola\CropRangeController;
use App\Http\Controllers\Agricola\CropStepController;
use App\Http\Controllers\Agricola\DashboardController;
use App\Http\Controllers\Agricola\DraftWeeklyPlanController;
use App\Http\Controllers\Agricola\DraftWeeklyPlanTaskController;
use App\Http\Controllers\Agricola\FincaController;
use App\Http\Controllers\Agricola\FincaGroupController;
use App\Http\Controllers\Agricola\LoteController;
use App\Http\Controllers\Agricola\RecalculateTask;
use App\Http\Controllers\Agricola\RecipeController;
use App\Http\Controllers\Agricola\SupplyController;
use App\Http\Controllers\Agricola\TaskController;
use App\Http\Controllers\Agricola\TaskGuidelinesController;
use App\Http\Controllers\Agricola\TaskGuidelineSupplyController;
use App\Http\Controllers\Agricola\WeeklyPlanController;
use App\Http\Controllers\Agricola\WeeklyPlanEmployeeController;
use App\Http\Controllers\Agricola\WeeklyPlanTaskController;
use App\Http\Controllers\Agricola\WeeklyPlanTaskCropEmployeeController;
use App\Http\Controllers\Agricola\WeeklyPlanTaskCropInputController;
use App\Http\Controllers\Agricola\WeeklyPlanTaskEmployeeController;
use App\Http\Controllers\Agricola\WeeklyPlanTaskPartialClosureController;
use App\Http\Controllers\WeeklyPlanTaskCropController;
use App\Http\Controllers\WeeklyPlanTaskInsumoController;
use Illuminate\Support\Facades\Route;

//CRUDS
Route::middleware('jwt.auth')->group(function () {

    Route::middleware('administrate_agricola')->group(function(){
        Route::apiResource('/fincas',                                       FincaController::class);
        Route::apiResource('/tasks',                                        TaskController::class);
        Route::apiResource('/lotes',                                        LoteController::class);
        Route::apiResource('/crops',                                        CropController::class);
        Route::apiResource('/crops-inputs',                                 CropInputController::class);
        Route::apiResource('/crops-parameters',                             CropParameterController::class);
        Route::apiResource('/crops-ranges',                                 CropRangeController::class);
        Route::apiResource('/crops-calculation-steps',                      CropStepController::class);
        Route::apiResource('/recipes',                                      RecipeController::class);
        Route::apiResource('/cdps',                                         CdpController::class);
        Route::apiResource('/finca-groups',                                 FincaGroupController::class);
        Route::apiResource('/weekly-plan-tasks-crop-inputs',                WeeklyPlanTaskCropInputController::class);
        Route::apiResource('/task-guidelines',                              TaskGuidelinesController::class);
        Route::apiResource('/task-guidelines-supplies',                     TaskGuidelineSupplyController::class);
        Route::apiResource('/draft-weekly-plans',                           DraftWeeklyPlanController::class);
        Route::apiResource('/draft-weekly-plan-tasks',                      DraftWeeklyPlanTaskController::class);
    });

    Route::apiResource('/supplies',                                     SupplyController::class);
    Route::apiResource('/weekly-plans',                                 WeeklyPlanController::class);
    Route::apiResource('/weekly-plan-tasks',                            WeeklyPlanTaskController::class);
    Route::apiResource('/weekly-plan-tasks-crops',                      WeeklyPlanTaskCropController::class);
    Route::apiResource('/weekly-plan-task-supplies',                    WeeklyPlanTaskInsumoController::class);
    Route::apiResource('/weekly-plan-employees',                        WeeklyPlanEmployeeController::class);
    Route::apiResource('/weekly-plan-task-employees',                   WeeklyPlanTaskEmployeeController::class);
    Route::apiResource('/weekly-plan-task-crop-employees',              WeeklyPlanTaskCropEmployeeController::class);
    Route::apiResource('/weekly-plan-task-partial-closures',            WeeklyPlanTaskPartialClosureController::class);
});

//FUNCTIONALITYS
Route::middleware('jwt.auth')->group(function () {
    Route::middleware('administrate_agricola')->group(function(){
        //TASKS
        Route::post('/tasks/uploadFile',                                                [TaskController::class, 'uploadFile']);
    
        //WEEKLY PLANS
        Route::post('/weekly-plans/uploadTasks/{id}',                                   [WeeklyPlanController::class, 'uploadTasksToWeeklyPlan']);
    
        //WEEKLY PLANS EMPLOYEE
        Route::post('/weekly-plan-employees/addEmployeesToFincaGroup/{id}',             [WeeklyPlanEmployeeController::class, 'addEmployeesToFincaGroup']);
        
        //PARTIAL CLOSURES
        Route::post('/weekly-plan-task-partial-closures/addOrUpdate',                   [WeeklyPlanTaskPartialClosureController::class, 'addOrUpdatePartialClosure']);
        
        //WEEKLY PLAN TASKS
        Route::post('/weekly-plan-tasks/cleanTask/{id}',                                [WeeklyPlanTaskController::class, 'cleanWeeklyPlanTask']);
        Route::get('/weekly-plan-tasks/getPayments/{id}',                               [WeeklyPlanTaskController::class, 'getWeeklyPlanTaskPayments']);
        Route::post('/weekly-plan-tasks/recalculate/{id}',                              RecalculateTask::class);

        //WEEKLY PLAN TASKS CROP
        Route::post('/weekly-plan-tasks-crops/calculate/{id}',                          [WeeklyPlanTaskCropController::class, 'calculateWeeklyPlanTaskCrop']);
        Route::get('/weekly-plan-tasks-crops/getPayments/{id}',                         [WeeklyPlanTaskCropController::class, 'getWeeklyPlanTaskCropPayments']);

        //DASHBOARD
        Route::get('/dashboard/summaryTasksByFinca',                                    [DashboardController::class, 'summaryTasksByFinca']);
        Route::get('/dashboard/activeTasks',                                            [DashboardController::class, 'getActiveTasks']);

        //TASKGUIDELINES
        Route::post('/task-guidelines/uploadFile',                                      [TaskGuidelinesController::class, 'uploadFile']);

        //CDPS
        Route::post('/cdps/explodeTasks/{id}',                                          [CdpController::class, 'explodeTasks']);
        Route::post('/cdps/cleanDraftTasks/{id}',                                       [CdpController::class, 'cleanDraftTasks']);
    });

    //FINCA GROUPS
    Route::get('/finca-groups/groupsSummaryByWeeklyPlan/{id}',                      [FincaGroupController::class, 'groupsSummaryByWeeklyPlan']);

    //WEEKLY PLAN TASKS
    Route::get('/weekly-plan-tasks/getTasksForCalendar/{weeklyPlanId}',             [WeeklyPlanTaskController::class, 'getWeeklyPlanTasksForCalendar']);
    Route::get('/weekly-plan-tasks/getTasksByLote/{weeklyPlanId}',                  [WeeklyPlanTaskController::class, 'getWeeklyPlanTasksGroupByCdp']);
    Route::get('/weekly-plan-tasks/getTasksByCdp/{weeklyPlanId}/{cdp}',             [WeeklyPlanTaskController::class, 'getWeeklyPlanTasksByCdp']);
    Route::post('/weekly-plan-tasks/startTask/{id}',                                [WeeklyPlanTaskController::class, 'startWeeklyPlanTask']);
    Route::post('/weekly-plan-tasks/closeTask/{id}',                                [WeeklyPlanTaskController::class, 'closeWeeklyPlanTask']);
    
    //WEEKLY PLAN TASKS CROP
    Route::get('/weekly-plan-tasks-crops/getTasksForCalendar/{weeklyPlanId}',       [WeeklyPlanTaskCropController::class, 'getWeeklyPlanTasksForCalendar']);
    Route::get('/weekly-plan-tasks-crops/getTasksGroupedByCdp/{weeklyPlanId}',      [WeeklyPlanTaskCropController::class, 'getWeeklyPlanTasksGroupByCdp']);
    Route::get('/weekly-plan-tasks-crops/getTasksByCdp/{weeklyPlanId}/{cdp}',       [WeeklyPlanTaskCropController::class, 'getWeeklyPlanTasksCropByCdp']);
    Route::post('/weekly-plan-tasks-crops/startTask/{id}',                          [WeeklyPlanTaskCropController::class, 'startWeeklyPlanTaskCrop']);
    Route::post('/weekly-plan-tasks-crops/closeTask/{id}',                          [WeeklyPlanTaskCropController::class, 'closeWeeklyPlanTaskCrop']);
});
