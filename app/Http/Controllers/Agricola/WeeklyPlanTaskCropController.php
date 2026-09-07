<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\WeeklyPlanTaskCrop\CloseWeeklyPlanTaskCrop;
use App\Http\Requests\Agricola\WeeklyPlanTaskCrop\CreateWeeklyPlanTaskCropRequest;
use App\Http\Requests\Agricola\WeeklyPlanTaskCrop\UpdateWeeklyPlanTaskCropRequest;
use App\Http\Resources\Agricola\WeeklyPlanTaskCropResource;
use App\Http\Resources\Agricola\WeeklyPlanTaskPaymentResource;
use App\Http\Resources\Agricola\WeeklyPlanTasksCropGroupedByCdpResource;
use App\Http\Resources\Agricola\WeeklyPlanTasksCropsForCalendarResource;
use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
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
            $result = $service->getWeeklyPlanTasksCrop($weeklyPlanId, $request);
            $data = WeeklyPlanTaskCropResource::collection($result);

            return ResponseHandler::success($data, 'Tareas de Cosecha Obtenidas Correctamente', 200);
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

            return ResponseHandler::success(new WeeklyPlanTaskCropResource($result), 'Tarea de Cosecha Obtenida Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWeeklyPlanTaskCropRequest $request, string $id, WeeklyPlanTaskCropServiceInterface $service)
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

    public function getWeeklyPlanTasksGroupByCdp(string $weeklyPlanId, WeeklyPlanServiceInterface $service)
    {
        try {
            $plan = $service->getWeeklyPlanById($weeklyPlanId);

            return ResponseHandler::success(new WeeklyPlanTasksCropGroupedByCdpResource($plan), 'Tareas de Cosecha Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function getWeeklyPlanTasksCropByCdp(string $weeklyPlanId, string $cdp, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $result = $service->getWeeklyPlanTasksCropByCdp($weeklyPlanId, $cdp);

            return ResponseHandler::success(WeeklyPlanTaskCropResource::collection($result), 'Tareas de Cosecha Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function startWeeklyPlanTaskCrop(string $id, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $service->startWeeklyPlanTaskCrop($id);

            return ResponseHandler::success(true, 'Tarea de Cosecha Iniciada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function closeWeeklyPlanTaskCrop(CloseWeeklyPlanTaskCrop $request, string $id, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $service->closeWeeklyPlanTaskCrop($id, $data);

            return ResponseHandler::success(true, 'Tarea de Cosecha Cerrada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function calculateWeeklyPlanTaskCrop(string $id, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $service->calculateWeeklyPlanTaskCrop($id);

            return ResponseHandler::success(true, 'Pagos de la Tarea de Cosecha Generados Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function cleanWeeklyPlanTaskCrop(string $id, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $service->cleanWeeklyPlanTaskCrop($id);

            return ResponseHandler::success(true, 'Tarea de Cosecha Limpiada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function getCropInputs(string $id, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $inputs = $service->getCropInputsForTask($id);

            return ResponseHandler::success($inputs, 'Parametros del Cultivo Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function getWeeklyPlanTaskCropPayments(string $id, WeeklyPlanTaskCropServiceInterface $service)
    {
        try {
            $payments = $service->getWeeklyPlanTaskCropPayments($id);

            return ResponseHandler::success(WeeklyPlanTaskPaymentResource::collection($payments), 'Pagos Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
