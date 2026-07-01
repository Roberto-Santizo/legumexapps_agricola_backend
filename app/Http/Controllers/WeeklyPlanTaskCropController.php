<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHandler;
use App\Http\Requests\Agricola\WeeklyPlanTaskCrop\CreateWeeklyPlanTaskCropRequest;
use App\Http\Resources\Agricola\WeeklyPlanTasksCropsForCalendarResource;
use App\Interfaces\Agricola\WeeklyPlanTaskCropServiceInterface;
use Illuminate\Http\Request;

class WeeklyPlanTaskCropController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $weeklyPlanId = $request->query('weeklyPlanId');
            $result = $service->getWeeklyPlanTasksCrop($weeklyPlanId);

            return ResponseHandler::success($result, 'Tareas de Cosecha Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWeeklyPlanTaskCropRequest $request, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->createWeeklyPlanTaskCrop($data);

            return ResponseHandler::success($result, 'Tarea de Cosecha Creada Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $result = $service->getWeeklyPlanTaskCropById($id);

            return ResponseHandler::success($result, 'Tarea de Cosecha Obtenida Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateWeeklyPlanTaskCropRequest $request, string $id, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->updateWeeklyPlanTaskCropById($data, $id);

            return ResponseHandler::success($result, 'Tarea de Cosecha Actualizada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $result = $service->deleteWeeklyPlanTaskCropById($id);

            return ResponseHandler::success($result, 'Tarea de Cosecha Eliminada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function getWeeklyPlanTasksForCalendar(string $weeklyPlanId, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $tasks = $service->getWeeklyPlanTasksCrop($weeklyPlanId);
            $data = WeeklyPlanTasksCropsForCalendarResource::collection($tasks);

            
            return ResponseHandler::success($data, 'Tareas de Cosecha Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
