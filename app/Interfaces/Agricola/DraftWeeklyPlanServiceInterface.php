<?php

namespace App\Interfaces\Agricola;

interface DraftWeeklyPlanServiceInterface
{
    public function createDraftWeeklyPlan(array $data);
    public function getDraftWeeklyPlans(?string $limit);
    public function getDraftWeeklyPlanById(string $id);
    public function updateDraftWeeklyPlanById(array $data, string $id);
    public function deleteDraftWeeklyPlanById(string $id);
}
