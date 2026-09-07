<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
use App\Interfaces\Employees\EmployeesServiceInterface;
use Illuminate\Http\Request;

class GetFincaEmployees extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $id, WeeklyPlanServiceInterface $service, EmployeesServiceInterface $employeesService)
    {
        try {
            $weeklyPlan = $service->getWeeklyPlanById($id);
            $filtered = filter_var($request->query('filtered', true), FILTER_VALIDATE_BOOLEAN);
            $employees = $employeesService->getFincaEmployees($weeklyPlan, $filtered);

            return ResponseHandler::success($employees, 'Empleados Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
