<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\Fincas\CreateFincaRequest;
use App\Http\Requests\Agricola\Fincas\UpdateFincaRequest;
use App\Http\Resources\Agricola\FincaResource;
use App\Http\Resources\Agricola\PaginatedFincasResource;
use App\Interfaces\Agricola\FincaServiceInterface;
use Illuminate\Http\Request;

class FincaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, FincaServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');

            $data = $service->getFincas($limit);

            $data = $limit ? new PaginatedFincasResource($data) : FincaResource::collection($data);

            return ResponseHandler::success($data, 'Fincas Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFincaRequest $request, FincaServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $finca = $service->createFinca($data);

            return ResponseHandler::success($finca, 'Finca Creada Exitosamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id,  FincaServiceInterface $service)
    {
        try {
            $finca = $service->getFincaById($id);

            return ResponseHandler::success($finca, 'Finca Obtenida Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFincaRequest $request, string $id, FincaServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $service->updateFincaById($data, $id);

            return ResponseHandler::success(null, 'Finca Actualizada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
