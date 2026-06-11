<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHandler;
use App\Http\Requests\Agricola\WeeklyPlanTaskInsumo\UpdateTaskInsumoRequest;
use App\Interfaces\Agricola\WeeklyPlanTaskInsumoServiceInterface;
use Illuminate\Http\Request;

class WeeklyPlanTaskInsumoController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id, WeeklyPlanTaskInsumoServiceInterface $service)
    {
        try {
            $insumo = $service->getInsumoById($id);
            return ResponseHandler::success($insumo, 'Insumo Obtenido Correctamente', 200);
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
