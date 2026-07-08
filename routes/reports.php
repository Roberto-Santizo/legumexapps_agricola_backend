<?php

use App\Http\Controllers\Reports\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt.auth')->group(function () {
    Route::middleware('administrate_agricola')->group(function(){
        Route::post('/reports/personal-details/{id}',           [ReportController::class, 'generatePersonalDetailsReport'])->middleware('administrate_agricola');
        Route::post('/reports/planification-details/{id}',      [ReportController::class, 'generatePlanificationDetailsReport'])->middleware('administrate_agricola');
        Route::post('/reports/planilla/{id}',                   [ReportController::class, 'generatePlanillaReport'])->middleware('administrate_agricola');
    });
});
