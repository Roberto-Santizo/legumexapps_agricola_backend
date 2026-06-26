<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\WeeklyPlanTaskPartialClosures\CreatePartialClosureRequest;
use App\Http\Requests\Agricola\WeeklyPlanTaskPartialClosures\UpdateWeeklyPlanPartialClosure;
use App\Http\Resources\Agricola\WeeklyPlanTaskPartialClosureResource;
use App\Interfaces\Agricola\WeeklyPlanTaskPartialClosureServiceInterface;
use Illuminate\Http\Request;

class WeeklyPlanTaskPartialClosureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,  WeeklyPlanTaskPartialClosureServiceInterface $service)
    {
        try {
            $taskId = $request->query('taskId');
            $partialClosures = $service->getPartialClosures($taskId);
            $data = WeeklyPlanTaskPartialClosureResource::collection($partialClosures);

            return ResponseHandler::success($data, 'Cierres Parciales Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePartialClosureRequest $request, WeeklyPlanTaskPartialClosureServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $partialClosure = $service->createPartialClosure($data);

            return ResponseHandler::success($partialClosure, 'Cierre Parcial Creado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, WeeklyPlanTaskPartialClosureServiceInterface $service)
    {
        try {
            $partialClosure = $service->getPartialClosureById($id);
            $data = new WeeklyPlanTaskPartialClosureResource($partialClosure);
            return ResponseHandler::success($data, 'Cierre Parcial Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWeeklyPlanPartialClosure $request, string $id, WeeklyPlanTaskPartialClosureServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $partialClosure = $service->updatePartialClosureById($id, $data);

            return ResponseHandler::success($partialClosure, 'Cierre Parcial Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, WeeklyPlanTaskPartialClosureServiceInterface $service)
    {
        try {
            $partialClosure = $service->deletePartialClosureById($id);

            return ResponseHandler::success($partialClosure, 'Cierre Eliminado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function addOrUpdatePartialClosure(Request $request, WeeklyPlanTaskPartialClosureServiceInterface $service)
    {
        try {
            $data = $request->validate(['task_weekly_plan_id' => ['required','exists:task_weekly_plans,id']]);

            $partialClosure = $service->addOrEditPartialClosure($data);

            return ResponseHandler::success($partialClosure, 'Tarea Actualizada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
