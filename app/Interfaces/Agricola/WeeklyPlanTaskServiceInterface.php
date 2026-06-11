<?php

namespace App\Interfaces\Agricola;

interface WeeklyPlanTaskServiceInterface
{
    public function createWeeklyPlanTask(array $data);
    public function getWeeklyPlanTasks(?string $limit);
    public function getWeeklyPlanTaskById(string $id);
    public function updateWeeklyPlanTaskById(array $data, string $id);
    public function deleteWeeklyPlanTaskById(string $id);
}
