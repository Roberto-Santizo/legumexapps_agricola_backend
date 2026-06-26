<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\CropParameters\CreateCropParameterRequest;
use App\Interfaces\Agricola\CropParameterServiceInterface;
use Illuminate\Http\Request;

class CropParameterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CropParameterServiceInterface $service)
    {
        try {
            $cropId = $request->query('cropId');
            $parameter = $service->getCropParameters($cropId);

            return ResponseHandler::success($parameter, 'Parametros Obtenidos Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCropParameterRequest $request, CropParameterServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $parameter = $service->createCropParameter($data);

            return ResponseHandler::success($parameter, 'Parametro Configurado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id,  CropParameterServiceInterface $service)
    {
        try {
            $parameter = $service->getCropParameterById($id);

            return ResponseHandler::success($parameter, 'Parametro Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCropParameterRequest $request, string $id, CropParameterServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $parameter = $service->updateCropParameterById($id, $data);

            return ResponseHandler::success($parameter, 'Parametro Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, CropParameterServiceInterface $service)
    {
        try {
            $parameter = $service->deleteCropParamaterById($id);

            return ResponseHandler::success($parameter, 'Parametro Eliminado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
