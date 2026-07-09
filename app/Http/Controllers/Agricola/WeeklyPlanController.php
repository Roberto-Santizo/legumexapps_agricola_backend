<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\WeeklyPlans\CreateWeeklyPlanRequest;
use App\Http\Requests\Shared\UploadFileRequest;
use App\Http\Resources\Agricola\PaginatedWeeklyPlansResource;
use App\Http\Resources\Agricola\WeeklyPlanResource;
use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
use Illuminate\Http\Request;

class WeeklyPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WeeklyPlanServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $user = auth()->user();
            $plans = $service->getWeeklyPlans($limit, $user);
            $data = $limit ? new PaginatedWeeklyPlansResource($plans) : WeeklyPlanResource::collection($plans);

            return ResponseHandler::success($data, 'Planes Semanales Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWeeklyPlanRequest $request, WeeklyPlanServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $weekly_plan = $service->createWeeklyPlan($data);

            return ResponseHandler::success($weekly_plan, 'Plan Semanal Creado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, WeeklyPlanServiceInterface $service)
    {
        try {
            $weekly_plan = $service->getWeeklyPlanById($id);

            return ResponseHandler::success(new WeeklyPlanResource($weekly_plan), 'Plan Semanal Creado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function update(CreateWeeklyPlanRequest $request, string $id, WeeklyPlanServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $service->updateWeeklyPlan($data, $id);

            return ResponseHandler::success(null, 'Plan Semanal Creado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function uploadTasksToWeeklyPlan(UploadFileRequest $request, string $id, WeeklyPlanServiceInterface $service)
    {
        try {
            $file = $request->validated();
            $service->uploadTasksToWeeklyPlan($file['file'], $id);

            return ResponseHandler::success(null, 'Tareas Cargadas Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
