<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
use App\Interfaces\Employees\EmployeesServiceInterface;

class GetFincaEmployees extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $id, WeeklyPlanServiceInterface $service, EmployeesServiceInterface $employeesService)
    {
        try {
            $weeklyPlan = $service->getWeeklyPlanById($id);
            $employees = $employeesService->getFincaEmployees($weeklyPlan);

            return ResponseHandler::success($employees, 'Empleados Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
