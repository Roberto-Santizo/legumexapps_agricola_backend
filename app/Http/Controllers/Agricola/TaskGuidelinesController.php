<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\TaskGuidelines\CreateTaskGuidelineRequest;
use App\Http\Requests\Shared\UploadFileRequest;
use App\Http\Resources\Agricola\PaginatedTaskGuidelinesResource;
use App\Http\Resources\Agricola\TaskGuidelineResource;
use App\Interfaces\Agricola\TaskGuidelinesServiceInterface;
use Illuminate\Http\Request;

class TaskGuidelinesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TaskGuidelinesServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $response = $service->getTaskGuidelines($limit);

            $data = $limit ? new PaginatedTaskGuidelinesResource($response) : TaskGuidelineResource::collection($response);

            return ResponseHandler::success($data, 'Tareas Guía Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskGuidelineRequest $request, TaskGuidelinesServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $response = $service->createTaskGuideline($data);

            return ResponseHandler::success($response, 'Tarea Guía creada correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id,  TaskGuidelinesServiceInterface $service)
    {
        try {
            $response = $service->getTaskGuidelineById($id);

            return ResponseHandler::success(new TaskGuidelineResource($response), 'Tarea Guía Obtenida correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateTaskGuidelineRequest $request, string $id,  TaskGuidelinesServiceInterface $service)
    {
        try {
            $data= $request->validated();
            $response = $service->updateTaskGuidelineById($data, $id);

            return ResponseHandler::success($response, 'Tarea Guía Actualizada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, TaskGuidelinesServiceInterface $service)
    {
        try {
            $response = $service->deleteTaskGuidelineById($id);

            return ResponseHandler::success($response, 'Tarea Guía Eliminada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function uploadFile(UploadFileRequest $request, TaskGuidelinesServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $service->uploadFile($data['file']);

            return ResponseHandler::success(null, 'Tareas Guías Cargadas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
