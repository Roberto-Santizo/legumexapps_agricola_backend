<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHandler;
use App\Http\Requests\Agricola\WeeklyPlanTaskInsumo\AddInsumoToTaskRequest;
use App\Http\Requests\Agricola\WeeklyPlanTaskInsumo\UpdateTaskInsumoRequest;
use App\Http\Resources\Agricola\WeeklyPlanTaskInsumoResource;
use App\Interfaces\Agricola\WeeklyPlanTaskInsumoServiceInterface;
use Illuminate\Http\Request;

class WeeklyPlanTaskInsumoController extends Controller
{

    public function index(Request $request, WeeklyPlanTaskInsumoServiceInterface $service)
    {
        try {
            $id = $request->query('taskId');
            $insumos = $service->getWeeklyPlanTaskInsumos($id);

            return ResponseHandler::success(WeeklyPlanTaskInsumoResource::collection($insumos), 'Insumos Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function store(AddInsumoToTaskRequest $request, WeeklyPlanTaskInsumoServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $insumo = $service->addInsumoToTask($data);
            return ResponseHandler::success($insumo, 'Insumo Creado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, WeeklyPlanTaskInsumoServiceInterface $service)
    {
        try {
            $insumo = $service->getInsumoById($id);
            return ResponseHandler::success(new WeeklyPlanTaskInsumoResource($insumo), 'Insumo Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskInsumoRequest $request, string $id, WeeklyPlanTaskInsumoServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $insumo = $service->updateInsumoById($data, $id);
            return ResponseHandler::success($insumo, 'Insumo Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, WeeklyPlanTaskInsumoServiceInterface $service)
    {
        try {
            $deleted = $service->deleteInsumoById($id);
            return ResponseHandler::success($deleted, 'Insumo Eliminado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
