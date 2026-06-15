<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\WeeklyPlanTasks\CreateWeeklyPlanTaskRequest;
use App\Http\Requests\Agricola\WeeklyPlanTasks\UpdateWeeklyPlanTaskRequest;
use App\Http\Resources\Agricola\WeeklyPlanTaskResource;
use App\Http\Resources\Agricola\WeeklyPlanTasksForCalendarResource;
use App\Interfaces\Agricola\WeeklyPlanTaskServiceInterface;
use Illuminate\Http\Request;

class WeeklyPlanTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WeeklyPlanTaskServiceInterface $service)
    {
        try {
            $id = $request->query('weeklyPlanId');
            $limit = $request->query('limit');

            $tasks = $service->getWeeklyPlanTasks($limit, $id);
            return ResponseHandler::success($tasks, 'Tareas Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWeeklyPlanTaskRequest $request, WeeklyPlanTaskServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $task = $service->createWeeklyPlanTask($data);

            return ResponseHandler::success($task, 'Tarea Agregada al Plan Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, WeeklyPlanTaskServiceInterface $service)
    {
        try {
            $task = $service->getWeeklyPlanTaskById($id);

            return ResponseHandler::success(new WeeklyPlanTaskResource($task), 'Tarea Obtenida Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWeeklyPlanTaskRequest $request, string $id, WeeklyPlanTaskServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $task = $service->updateWeeklyPlanTaskById($data, $id);

            return ResponseHandler::success($task, 'Tarea Actualizada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //!IMPLEMENT
    }

    public function startWeeklyPlanTask(string $id, WeeklyPlanTaskServiceInterface $service)
    {
        try {
            $service->startWeeklyPlanTask($id);

            return ResponseHandler::success(true, 'Tarea Iniciada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function closeWeeklyPlanTask(string $id, WeeklyPlanTaskServiceInterface $service)
    {
        try {
            // $service->startWeeklyPlanTask($id);

            return ResponseHandler::success(true, 'Tarea Cerrada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function getWeeklyPlanTasksForCalendar(string $id, WeeklyPlanTaskServiceInterface $service)
    {
        try {
            $tasks = $service->getWeeklyPlanTasksForCalendar($id);
            $data = WeeklyPlanTasksForCalendarResource::collection($tasks);

            return ResponseHandler::success($data, 'Tareas Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
