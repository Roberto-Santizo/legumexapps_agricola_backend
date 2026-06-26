<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\CropInput\CreateCropInputRequest;
use App\Interfaces\Agricola\CropInputServiceInterface;
use Illuminate\Http\Request;

class CropInputController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CropInputServiceInterface $service)
    {
        try {
            $cropId = $request->query('cropId');
            $inputs = $service->getCropInputs($cropId);

            return ResponseHandler::success($inputs, 'Inputs Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCropInputRequest $request, CropInputServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $input = $service->createCropInput($data);

            return ResponseHandler::success($input, 'Input Configurado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, CropInputServiceInterface $service)
    {
        try {
            $input = $service->getCropInputById($id);

            return ResponseHandler::success($input, 'Input Obtenido Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCropInputRequest $request, string $id, CropInputServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $input = $service->updateCropInputById($id, $data);

            return ResponseHandler::success($input, 'Input Actualizado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, CropInputServiceInterface $service)
    {
         try {
            $input = $service->deleteCropInputById($id);

            return ResponseHandler::success($input, 'Input Eliminado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
