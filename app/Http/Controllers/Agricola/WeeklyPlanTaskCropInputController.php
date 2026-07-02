<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\WeeklyPlanTaskCropInput\CreateParameterRequest;
use App\Interfaces\Agricola\WeeklyPlanTaskCropInputServiceInterface;
use Illuminate\Http\Request;

class WeeklyPlanTaskCropInputController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WeeklyPlanTaskCropInputServiceInterface $service)
    {
        try {
            $taskId = $request->query('taskId');
            $result = $service->getInputs($taskId);

            return ResponseHandler::success($result, 'Parametros Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateParameterRequest $request, WeeklyPlanTaskCropInputServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->createInput($data);

            return ResponseHandler::success($result, 'Parametro Creado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, WeeklyPlanTaskCropInputServiceInterface $service)
    {
        try {
            $result = $service->getInputById($id);

            return ResponseHandler::success($result, 'Parametro Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateParameterRequest $request, string $id, WeeklyPlanTaskCropInputServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->updateInputById($data, $id);

            return ResponseHandler::success($result, 'Parametro Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, WeeklyPlanTaskCropInputServiceInterface $service)
    {
        try {
            $result = $service->deleteInputById($id);

            return ResponseHandler::success($result, 'Parametro Eliminado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
