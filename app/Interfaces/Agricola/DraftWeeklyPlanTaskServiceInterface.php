<?php

namespace App\Interfaces\Agricola;

interface DraftWeeklyPlanTaskServiceInterface
{
    public function createDraftWeeklyPlanTask(array $data);
    public function getDraftWeeklyPlanTasks(?string $draftWeeklyPlanId);
    public function getDraftWeeklyPlanTaskById(string $id);
    public function updateDraftWeeklyPlanTaskById(array $data, string $id);
    public function deleteDraftWeeklyPlanTaskById(string $id);
}
