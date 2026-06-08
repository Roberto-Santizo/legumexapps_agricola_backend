<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\Crops\CreateCropRequest;
use App\Http\Requests\Agricola\Crops\UpdateCropRequest;
use App\Http\Resources\Agricola\CropResource;
use App\Http\Resources\Agricola\PaginatedCropsResource;
use App\Interfaces\Agricola\CropServiceInterface;

use Illuminate\Http\Request;

class CropController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CropServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $crops = $service->getCrops($limit);

            $data = $limit ? new PaginatedCropsResource($crops) : CropResource::collection($crops);

            return ResponseHandler::success($data, 'Cultivos Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCropRequest $request, CropServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $crop = $service->createCrop($data);

            return ResponseHandler::success($crop, 'Cultivo Creado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, CropServiceInterface $service)
    {
        try {
            $crop = $service->getCropById($id);
            $data = new CropResource($crop);

            return ResponseHandler::success($data, 'Cultivo Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropRequest $request, string $id, CropServiceInterface $service)
    {
        try {
            $data = $request->validated();

            $service->updateCropById($data, $id);

            return ResponseHandler::success(null, 'Cultivo actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
