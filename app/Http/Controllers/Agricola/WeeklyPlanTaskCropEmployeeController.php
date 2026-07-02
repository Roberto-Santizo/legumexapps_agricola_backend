<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\WeeklyPlanTaskCropEmployee\CreateEmployeeRequest;
use App\Interfaces\Agricola\WeeklyPlanTaskCropEmployeeServiceInterface;
use Illuminate\Http\Request;

class WeeklyPlanTaskCropEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WeeklyPlanTaskCropEmployeeServiceInterface $service)
    {
        try {
            $taskId = $request->query('taskId');
            $employees = $service->getWeeklyPlanTaskCropEmployees($taskId);

            return ResponseHandler::success($employees, 'Empleados Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request, WeeklyPlanTaskCropEmployeeServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->createWeeklyPlanTaskCropEmployee($data);

            return ResponseHandler::success($result, 'Empleado Agregado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, WeeklyPlanTaskCropEmployeeServiceInterface $service)
    {
        try {
            $employee = $service->getWeeklyPlanTaskCropEmployeeById($id);

            return ResponseHandler::success($employee, 'Empleado Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateEmployeeRequest $request, string $id, WeeklyPlanTaskCropEmployeeServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $employee = $service->updateWeeklyPlanTaskCropEmployee($data, $id);

            return ResponseHandler::success($employee, 'Empleado Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, WeeklyPlanTaskCropEmployeeServiceInterface $service)
    {
        try {
            $task = $service->deleteWeeklyPlanTaskCropEmployeeById($id);

            return ResponseHandler::success($task, 'Empleado Eliminado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
