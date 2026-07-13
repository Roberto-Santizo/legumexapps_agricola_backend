<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\TaskGuidelineSupplies\CreateTaskGuidelineSupplyRequest;
use App\Http\Resources\Agricola\TaskGuidelineSupplyResource;
use App\Interfaces\Agricola\TaskGuidelineSupplyServiceInterface;
use Illuminate\Http\Request;

class TaskGuidelineSupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TaskGuidelineSupplyServiceInterface $service)
    {
        try {
            $id = $request->query('taskGuidelineId');
            $result = $service->getTaskGuidelineSupplies($id);

            return ResponseHandler::success(TaskGuidelineSupplyResource::collection($result), 'Recetas Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskGuidelineSupplyRequest $request, TaskGuidelineSupplyServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->createTaskGuidelineSupply($data);


            return ResponseHandler::success($result, 'Receta Agregada Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, TaskGuidelineSupplyServiceInterface $service)
    {
         try {
            $result = $service->getTaskGuidelineSupplyById($id);

            return ResponseHandler::success(new TaskGuidelineSupplyResource($result), 'Receta Obtenida Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateTaskGuidelineSupplyRequest $request, string $id, TaskGuidelineSupplyServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->updateTaskGuidelineSupplyById($data, $id);

            return ResponseHandler::success($result, 'Receta Actualizada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, TaskGuidelineSupplyServiceInterface $service)
    {
        try {
            $result = $service->deleteTaskGuidelineSupplyById($id);

            return ResponseHandler::success($result, 'Receta Eliminada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
