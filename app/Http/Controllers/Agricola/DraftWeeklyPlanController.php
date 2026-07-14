<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\DraftWeeklyPlan\CreateDraftWeeklyPlanRequest;
use App\Http\Resources\Agricola\DraftWeeklyPlanResource;
use App\Http\Resources\Agricola\PaginatedDraftWeeklyPlansResource;
use App\Interfaces\Agricola\DraftWeeklyPlanServiceInterface;
use Illuminate\Http\Request;

class DraftWeeklyPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DraftWeeklyPlanServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $result = $service->getDraftWeeklyPlans($limit);
            $data = $limit ? new PaginatedDraftWeeklyPlansResource($result) : DraftWeeklyPlanResource::collection($result);

            return ResponseHandler::success($data, 'Drafts Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDraftWeeklyPlanRequest $request, DraftWeeklyPlanServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->createDraftWeeklyPlan($data);

            return ResponseHandler::success($result, 'Draft Creado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, DraftWeeklyPlanServiceInterface $service)
    {
        try {
            $result = $service->getDraftWeeklyPlanById($id);

            return ResponseHandler::success(new DraftWeeklyPlanResource($result), 'Draft Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateDraftWeeklyPlanRequest $request, string $id, DraftWeeklyPlanServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $result = $service->updateDraftWeeklyPlanById($data, $id);

            return ResponseHandler::success($result, 'Draft Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, DraftWeeklyPlanServiceInterface $service)
    {
        try {
            $result = $service->deleteDraftWeeklyPlanById($id);

            return ResponseHandler::success($result, 'Draft Eliminado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
