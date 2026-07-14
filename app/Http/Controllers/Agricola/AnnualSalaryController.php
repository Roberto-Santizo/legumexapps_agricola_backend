<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\AnnualSalaries\CreateAnnualSalaryRequest;
use App\Http\Requests\Agricola\AnnualSalaries\UpdateAnnualSalaryRequest;
use App\Http\Resources\Agricola\AnnualSalaryResource;
use App\Http\Resources\Agricola\PaginatedAnnualSalariesResource;
use App\Interfaces\Agricola\AnnualSalaryServiceInterface;
use Illuminate\Http\Request;

class AnnualSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AnnualSalaryServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $annualSalaries = $service->getAnnualSalaries($limit);
            $data = $limit ? new PaginatedAnnualSalariesResource($annualSalaries) : AnnualSalaryResource::collection($annualSalaries);

            return ResponseHandler::success($data, 'Salarios Anuales Obtenidos Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAnnualSalaryRequest $request, AnnualSalaryServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $annualSalary = $service->createAnnualSalary($data);

            return ResponseHandler::success($annualSalary, 'Salario Anual Creado Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, AnnualSalaryServiceInterface $service)
    {
        try {
            $annualSalary = $service->getAnnualSalaryById($id);

            return ResponseHandler::success($annualSalary, 'Salario Anual Obtenido Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnnualSalaryRequest $request, string $id, AnnualSalaryServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $annualSalary = $service->updateAnnualSalaryById($id, $data);

            return ResponseHandler::success($annualSalary, 'Salario Anual Actualizado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, AnnualSalaryServiceInterface $service)
    {
        try {
            $annualSalary = $service->deleteAnnualSalaryById($id);

            return ResponseHandler::success($annualSalary, 'Salario Anual Eliminado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
