<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\WeeklyPlanEmployees\AddEmployeesToGroupRequest;
use App\Http\Requests\Agricola\WeeklyPlanEmployees\CreateWeeklyPlanEmployeeRequest;
use App\Http\Resources\Agricola\WeeklyPlanEmployeeResource;
use App\Interfaces\Agricola\WeeklyPlanEmployeeServiceInterface;
use Illuminate\Http\Request;

class WeeklyPlanEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WeeklyPlanEmployeeServiceInterface $service)
    {
        try {
            $weeklyPlanId = $request->query('weeklyPlanId');

            $employees = $service->getWeeklyPlanEmployees($weeklyPlanId);

            return ResponseHandler::success(WeeklyPlanEmployeeResource::collection($employees), 'Empleados Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWeeklyPlanEmployeeRequest $request, WeeklyPlanEmployeeServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $employee = $service->createWeeklyPlanEmployee($data);

            return ResponseHandler::success($employee, 'Empleado Creado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, WeeklyPlanEmployeeServiceInterface $service)
    {
        try {
            $employee = $service->getWeeklyPlanEmployeeById($id);

            return ResponseHandler::success($employee, 'Empleado Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateWeeklyPlanEmployeeRequest $request, string $id, WeeklyPlanEmployeeServiceInterface $service)
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
    public function destroy(string $id, WeeklyPlanEmployeeServiceInterface $service)
    {
        try {
            $service->deleteWeeklyPlanEmployeeById($id);

            return ResponseHandler::success(true, 'Empleado Eliminado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function addEmployeesToFincaGroup(AddEmployeesToGroupRequest $request, string $id, WeeklyPlanEmployeeServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $service->addEmployeesToFincaGrup($data, $id);

            return ResponseHandler::success(true, 'Empleados Añadidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
