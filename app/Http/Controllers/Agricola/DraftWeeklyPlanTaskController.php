<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\DraftWeeklyPlanTask\CreateDraftWeeklyPlanTaskRequest;
use App\Http\Resources\Agricola\DraftWeeklyPlanTaskResource;
use App\Interfaces\Agricola\DraftWeeklyPlanTaskServiceInterface;
use Illuminate\Http\Request;

class DraftWeeklyPlanTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DraftWeeklyPlanTaskServiceInterface $service)
    {
        try {
            $draftWeeklyPlanId = $request->query('draftWeeklyPlanId');
            $result = $service->getDraftWeeklyPlanTasks($draftWeeklyPlanId);

            return ResponseHandler::success(DraftWeeklyPlanTaskResource::collection($result), 'Drafts Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDraftWeeklyPlanTaskRequest $request, DraftWeeklyPlanTaskServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->createDraftWeeklyPlanTask($data);

            return ResponseHandler::success($result, 'Draft de Tarea Creada Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, DraftWeeklyPlanTaskServiceInterface $service)
    {
        try {
            $result = $service->getDraftWeeklyPlanTaskById($id);

            return ResponseHandler::success(new DraftWeeklyPlanTaskResource($result), 'Draft Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateDraftWeeklyPlanTaskRequest $request, string $id, DraftWeeklyPlanTaskServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->updateDraftWeeklyPlanTaskById($data, $id);

            return ResponseHandler::success($result, 'Draft Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, DraftWeeklyPlanTaskServiceInterface $service)
    {
        try {
            $result = $service->deleteDraftWeeklyPlanTaskById($id);

            return ResponseHandler::success($result, 'Draft Eliminado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
