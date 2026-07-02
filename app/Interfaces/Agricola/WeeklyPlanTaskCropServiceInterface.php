<?php

namespace App\Interfaces\Agricola;

interface WeeklyPlanTaskCropServiceInterface
{
    public function createWeeklyPlanTaskCrop(array $data);
    public function getWeeklyPlanTasksCrop(?string $weeklyPlanId);
    public function getWeeklyPlanTaskCropById(string $id);
    public function updateWeeklyPlanTaskCropById(array $data, string $id);
    public function deleteWeeklyPlanTaskCropById(string $id);
    public function getWeeklyPlanTasksCropByCdp(string $weeklyPlanId, string $cdp);
}
