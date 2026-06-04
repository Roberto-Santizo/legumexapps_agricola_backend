<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\CreateTaskRequest;
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
