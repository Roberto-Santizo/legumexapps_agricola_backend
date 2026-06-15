<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\WeeklyPlanTaskEmployee\AddEmployeeToWeeklyPlanTaskRequest;
use App\Interfaces\Agricola\WeeklyPlanTaskEmployeeServiceInterface;
use Illuminate\Http\Request;

class WeeklyPlanTaskEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WeeklyPlanTaskEmployeeServiceInterface $service)
    {
        try {
            $taskWeeklyPlanId = $request->query('taskId');
            $employees = $service->getWeeklyPlanTaskEmployees($taskWeeklyPlanId);

            return ResponseHandler::success($employees, 'Empleados Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddEmployeeToWeeklyPlanTaskRequest $request, WeeklyPlanTaskEmployeeServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $employee = $service->addEmployeeToWeeklyPlanTask($data);
            return ResponseHandler::success($employee, 'Empleado Agregado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, WeeklyPlanTaskEmployeeServiceInterface $service)
    {
        try {
            $employee = $service->getWeeklyPlanTaskEmployeeById($id);

            return ResponseHandler::success($employee, 'Empleado Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddEmployeeToWeeklyPlanTaskRequest $request, string $id, WeeklyPlanTaskEmployeeServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $employee = $service->updateWeeklyPlanEmployeeById($data, $id);
            return ResponseHandler::success($employee, 'Empleado Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, WeeklyPlanTaskEmployeeServiceInterface $service)
    {
         try {
            $employee = $service->deleteWeeklyPlanEmployeeById($id);

            return ResponseHandler::success($employee, 'Empleado Elimiado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
