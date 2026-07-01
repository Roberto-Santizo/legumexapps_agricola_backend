<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanTaskCropServiceInterface;
use App\Models\Agricola\WeeklyPlanTaskCrop;
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
        if (!$weeklyPlanId) throw new BadRequestError("El ID del plan es requerido");
        $tasks = WeeklyPlanTaskCrop::where('weekly_plan_id', $weeklyPlanId)->get();
        return $tasks;
    }

    #[Override]
    public function getWeeklyPlanTaskCropById(string $id)
    {
        $task = WeeklyPlanTaskCrop::find($id);
        if (!$task) throw new NotFoundError("La tarea de cosecha no existe");
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
}
