<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\Lotes\CreateLoteRequest;
use App\Http\Requests\Agricola\Lotes\UpdateLoteRequest;
use App\Http\Resources\Agricola\LoteResource;
use App\Http\Resources\Agricola\PaginatedLotesResource;
use App\Interfaces\Agricola\LoteServiceInterface;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, LoteServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $lotes = $service->getLotes($limit);

            $data = $limit ? new PaginatedLotesResource($lotes) : LoteResource::collection($lotes);

            return ResponseHandler::success($data, 'Lotes Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLoteRequest $request, LoteServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $service->createLote($data);
            return ResponseHandler::success(null, 'Lote Creado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, LoteServiceInterface $service)
    {
        try {
            $lote = $service->getLoteById($id);
            return ResponseHandler::success(new LoteResource($lote), 'Lote Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoteRequest $request, string $id, LoteServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $service->updateLoteById($data, $id);

            return ResponseHandler::success(null, 'Lote Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
