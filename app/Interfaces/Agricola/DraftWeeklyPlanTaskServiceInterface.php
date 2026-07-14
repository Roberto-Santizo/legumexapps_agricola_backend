<?php

namespace App\Interfaces\Agricola;

use Illuminate\Http\Request;

interface DraftWeeklyPlanTaskServiceInterface
{
    public function createDraftWeeklyPlanTask(array $data);
    public function getDraftWeeklyPlanTasks(Request $request, ?string $draftWeeklyPlanId);
    public function getDraftWeeklyPlanTaskById(string $id);
    public function updateDraftWeeklyPlanTaskById(array $data, string $id);
    public function deleteDraftWeeklyPlanTaskById(string $id);
}
