<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\DraftWeeklyPlanTaskServiceInterface;
use App\Models\Agricola\DraftWeeklyPlanTask;
use Override;

class DraftWeeklyPlanTaskService implements DraftWeeklyPlanTaskServiceInterface
{
    #[Override]
    public function createDraftWeeklyPlanTask(array $data)
    {
        $newTask = DraftWeeklyPlanTask::create($data);
        return $newTask;
    }

    #[Override]
    public function getDraftWeeklyPlanTasks(?string $draftWeeklyPlanId)
    {
        if(!$draftWeeklyPlanId) throw new BadRequestError("El ID del draft plan es requerido");
        $tasks = DraftWeeklyPlanTask::where('draft_weekly_plan_id', '=', $draftWeeklyPlanId)->get();
        return $tasks;
    }

    #[Override]
    public function getDraftWeeklyPlanTaskById(string $id)
    {
        $draftTask = DraftWeeklyPlanTask::find($id, ['*']);
        if(!$draftTask) throw new NotFoundError("El draft no existe");
        return $draftTask;
        
    }

    #[Override]
    public function updateDraftWeeklyPlanTaskById(array $data, string $id)
    {
        $draftTask = $this->getDraftWeeklyPlanTaskById($id);
        $draftTask->update($data);
        return true;
    }

    #[Override]
    public function deleteDraftWeeklyPlanTaskById(string $id)
    {
        $draftTask = $this->getDraftWeeklyPlanTaskById($id);
        $draftTask->delete();
        return true;
    }
}
