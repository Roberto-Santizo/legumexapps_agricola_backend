<?php

namespace App\Interfaces\Agricola;

use Illuminate\Http\Request;

interface WeeklyPlanTaskCropServiceInterface
{
    public function createWeeklyPlanTaskCrop(array $data);

    public function getWeeklyPlanTasksCrop(?string $weeklyPlanId, ?Request $request = null);

    public function getWeeklyPlanTaskCropById(string $id);

    public function updateWeeklyPlanTaskCropById(array $data, string $id);

    public function deleteWeeklyPlanTaskCropById(string $id);

    public function getWeeklyPlanTasksCropByCdp(string $weeklyPlanId, string $cdp);

    public function startWeeklyPlanTaskCrop(string $id);

    public function closeWeeklyPlanTaskCrop(string $id, array $data);

    public function calculateWeeklyPlanTaskCrop(string $id);

    public function cleanWeeklyPlanTaskCrop(string $id);

    public function getCropInputsForTask(string $id);

    public function getWeeklyPlanTaskCropPayments(string $id);
}
