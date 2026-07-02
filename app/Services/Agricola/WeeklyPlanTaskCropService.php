<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotAcceptable;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanTaskCropInputServiceInterface;
use App\Interfaces\Agricola\WeeklyPlanTaskCropServiceInterface;
use App\Models\Agricola\WeeklyPlanTaskCrop;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Override;

class WeeklyPlanTaskCropService implements WeeklyPlanTaskCropServiceInterface
{
    #[Override]
    public function createWeeklyPlanTaskCrop(array $data)
    {
        $task = WeeklyPlanTaskCrop::create($data);

        return $task;
    }

    #[Override]
    public function getWeeklyPlanTasksCrop(?string $weeklyPlanId)
    {
        if (! $weeklyPlanId) {
            throw new BadRequestError('El ID del plan es requerido');
        }
        $tasks = WeeklyPlanTaskCrop::where('weekly_plan_id', $weeklyPlanId)->get();

        return $tasks;
    }

    #[Override]
    public function getWeeklyPlanTaskCropById(string $id)
    {
        $task = WeeklyPlanTaskCrop::find($id);
        if (! $task) {
            throw new NotFoundError('La tarea de cosecha no existe');
        }

        return $task;
    }

    #[Override]
    public function updateWeeklyPlanTaskCropById(array $data, string $id)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);
        $task->update($data);

        return true;
    }

    #[Override]
    public function deleteWeeklyPlanTaskCropById(string $id)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);
        $task->delete();

        return true;
    }

    #[Override]
    public function getWeeklyPlanTasksCropByCdp(string $weeklyPlanId, string $cdp)
    {
        $tasks = WeeklyPlanTaskCrop::where('weekly_plan_id', $weeklyPlanId)
            ->where('operation_date', Carbon::today())
            ->whereHas('cdp', fn ($q) => $q->where('name', $cdp))
            ->with('cdp')
            ->get();

        return $tasks;
    }

    #[Override]
    public function startWeeklyPlanTaskCrop(string $id)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);
        if ($task->employees->count() < 0) {
            throw new NotAcceptable('La tarea no cuenta con empleados asignados');
        }
        $task->status = 2;
        $task->save();

        return true;
    }

    #[Override]
    public function closeWeeklyPlanTaskCrop(string $id, array $data, WeeklyPlanTaskCropInputServiceInterface $service)
    {
        DB::transaction(function() use($data, $id, $service) {
            $task = $this->getWeeklyPlanTaskCropById($id);
            $isReadyToClose = $task->employees()->whereNull('lbs')->exists();
            if ($isReadyToClose) throw new NotAcceptable('La tarea cuenta con empleados sin libras registradas');
                
            foreach ($data['inputs'] as $input) {
                $input['task_crop_weekly_plan_id'] = $data['task_crop_weekly_plan_id'];
                $service->createInput($input);
            }
                    
            $task->status = 3;
            $task->save();
        });

        return true;
    }
}
