<?php

namespace App\Http\Controllers\Agricola;

use App\Actions\WeeklyPlanTasks\CloseWeeklyPlanTaskAction;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Services\Agricola\WeeklyPlanTaskService;

class RecalculateTask extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $id, WeeklyPlanTaskService $service, CloseWeeklyPlanTaskAction $action)
    {
        try {
            $task = $service->getWeeklyPlanTaskById($id);
            $action->execute($task);
            
            return ResponseHandler::success(null, 'Recalculo Realizado Correctamente', 200); 
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}
