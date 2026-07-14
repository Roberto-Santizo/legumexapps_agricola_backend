<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\DraftWeeklyPlanTaskServiceInterface;
use App\Models\Agricola\DraftWeeklyPlanTask;
use Illuminate\Http\Request;
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
    public function getDraftWeeklyPlanTasks(Request $request, ?string $draftWeeklyPlanId)
    {
        if(!$draftWeeklyPlanId) throw new BadRequestError("El ID del draft plan es requerido");
        $query = DraftWeeklyPlanTask::query();
        $query->where('draft_weekly_plan_id', '=', $draftWeeklyPlanId);
        if($request->query('cdp')) $query->where('plantation_control_id', $request->query('cdp'));


        return $query->get();
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
