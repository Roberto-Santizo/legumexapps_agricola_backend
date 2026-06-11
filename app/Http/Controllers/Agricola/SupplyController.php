<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\Supplies\CreateSupplyRequest;
use App\Http\Requests\Agricola\Supplies\UpdateSupplyRequest;
use App\Http\Resources\Agricola\PaginatedSuppliesResource;
use App\Http\Resources\Agricola\SupplyResource;
use App\Interfaces\Agricola\SupplyServiceInterface;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, SupplyServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $supplies = $service->getSupplies($limit);

            $data = $limit ? new PaginatedSuppliesResource($supplies) : SupplyResource::collection($supplies);

            return ResponseHandler::success($data, 'Insumos Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSupplyRequest $request, SupplyServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $supply = $service->createSupply($data);

            return ResponseHandler::success($supply, 'Insumo Creado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, SupplyServiceInterface $service)
    {
        try {
            $supply = $service->getSupplyById($id);

            return ResponseHandler::success($supply, 'Insumo Creado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplyRequest $request, string $id, SupplyServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $supply = $service->updateSupplyById($data, $id);

            return ResponseHandler::success($supply, 'Insumo Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
