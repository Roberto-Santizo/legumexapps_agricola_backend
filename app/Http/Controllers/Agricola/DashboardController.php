<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\Agricola\SummaryTasksByFincaResource;
use App\Http\Resources\Agricola\WeeklyPlanTaskResource;
use App\Interfaces\Agricola\DashboardServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summaryTasksByFinca(Request $request, DashboardServiceInterface $service)
    {
        try {
            $now = Carbon::now();
            $week = $now->weekOfYear();
            $year = $now->weekYear();

            $summary = $service->summaryTasksByFinca($week, $year);

            return ResponseHandler::success(SummaryTasksByFincaResource::collection($summary), 'Resumen Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function getActiveTasks(Request $request, DashboardServiceInterface $service)
    {
        try {
            $now = Carbon::now();
            $week = $now->weekOfYear();
            $year = $now->weekYear();

            $tasks = $service->getActiveTasks($week, $year);

            return ResponseHandler::success(WeeklyPlanTaskResource::collection($tasks), 'Resumen Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
