<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\Tasks\CreateTaskRequest;
use App\Http\Requests\Agricola\Tasks\UpdateTaskRequest;
use App\Http\Requests\Shared\UploadFileRequest;
use App\Http\Resources\Agricola\PaginatedTasksResource;
use App\Http\Resources\Agricola\TaskResource;
use App\Interfaces\Agricola\TaskServiceInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TaskServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $tasks = $service->getTasks($limit);

            $data = $limit ? new PaginatedTasksResource($tasks) : TaskResource::collection($tasks);
            return ResponseHandler::success($data, 'Tareas Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskRequest $request, TaskServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $task = $service->createTask($data);

            return ResponseHandler::success($task, 'Tarea Creada Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, TaskServiceInterface $service)
    {
        try {
            $task = $service->getTaskById($id);

            return ResponseHandler::success($task, 'Tarea Obtenida Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id, TaskServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $service->updateTaskById($data, $id);

            return ResponseHandler::success(null, 'Tarea Actualizada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function uploadFile(UploadFileRequest $request, TaskServiceInterface $service)
    {
        try {
            $file = $request->validated();

            $service->uploadTasksFromFile($file['file']);

            return ResponseHandler::success(null, 'Tareas Creadas Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
