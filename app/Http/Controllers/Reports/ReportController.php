<?php

namespace App\Http\Controllers\Reports;

use App\Exports\Agricola\WeeklyPlanificationReport;
use App\Exports\Agricola\WeeklyPlanillaReport;
use App\Exports\Agricola\WeeklyPlanPaymentDetails;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function generatePersonalDetailsReport(string $id, WeeklyPlanServiceInterface $service)
    {
        try {
            $plan = $service->getWeeklyPlanById($id);
            $file = Excel::raw(new WeeklyPlanPaymentDetails($plan), \Maatwebsite\Excel\Excel::XLSX);
            $filename = "Detalle de Personal S{$plan->week} {$plan->finca->name}";

            $response = [ 'file' => base64_encode($file), 'fileName' => $filename ];

            return ResponseHandler::success($response, 'Reporte Generado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function generatePlanificationDetailsReport(string $id, WeeklyPlanServiceInterface $service)
    {
        try {
            $plan = $service->getWeeklyPlanById($id);
            $file = Excel::raw(new WeeklyPlanificationReport($plan), \Maatwebsite\Excel\Excel::XLSX);
            $filename = "Detalle de Tareas S{$plan->week} {$plan->finca->name}";

            $response = [ 'file' => base64_encode($file), 'fileName' => $filename ];

            return ResponseHandler::success($response, 'Reporte Generado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    public function generatePlanillaReport(string $id, WeeklyPlanServiceInterface $service)
    {
        try {
            $plan = $service->getWeeklyPlanById($id);
            $file = Excel::raw(new WeeklyPlanillaReport($plan), \Maatwebsite\Excel\Excel::XLSX);
            $filename = "Planilla S{$plan->week} {$plan->finca->name}";

            $response = [ 'file' => base64_encode($file), 'fileName' => $filename ];

            return ResponseHandler::success($response, 'Reporte Generado Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
