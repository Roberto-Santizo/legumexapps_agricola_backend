<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\Cdps\CreateCdpRequest;
use App\Http\Requests\Agricola\Cdps\UpdateCdpRequest;
use App\Http\Resources\Agricola\CdpResource;
use App\Http\Resources\Agricola\PaginatedCdpsResource;
use App\Interfaces\Agricola\CdpServiceInterface;
use Illuminate\Http\Request;

class CdpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CdpServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $cdps = $service->getCdps($limit);

            $data = $limit ? new PaginatedCdpsResource($cdps) : CdpResource::collection($cdps);

            return ResponseHandler::success($data, 'Cdps Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCdpRequest $request, CdpServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $cdp = $service->createCdp($data);

            return ResponseHandler::success($cdp, 'Cdp Creado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, CdpServiceInterface $service)
    {
        try {
            $cdp = $service->getCdpById($id);
            $data = new CdpResource($cdp);

            return ResponseHandler::success($data, 'Cdp Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCdpRequest $request, string $id, CdpServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $cdp = $service->updateCdpById($data, $id);

            return ResponseHandler::success($cdp, 'Cdp Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
