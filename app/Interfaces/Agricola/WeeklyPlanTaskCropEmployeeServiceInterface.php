<?php

namespace App\Interfaces\Agricola;

interface WeeklyPlanTaskCropEmployeeServiceInterface
{
    public function createWeeklyPlanTaskCropEmployee(array $data);

    public function getWeeklyPlanTaskCropEmployees(?string $taskId);

    public function getWeeklyPlanTaskCropEmployeeById(string $id);

    public function updateWeeklyPlanTaskCropEmployee(array $data, string $id);

    public function deleteWeeklyPlanTaskCropEmployeeById(string $id);
}
