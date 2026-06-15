<?php

namespace App\Interfaces\Agricola;

use Illuminate\Support\Collection;

interface WeeklyPlanTaskEmployeeServiceInterface
{
    public function addEmployeeToWeeklyPlanTask(array $data);
    public function getWeeklyPlanTaskEmployees(?string $id);
    public function getWeeklyPlanTaskEmployeeById(string $id);
    public function updateWeeklyPlanEmployeeById(array $data, string $id);
    public function deleteWeeklyPlanEmployeeById(string $id);
    public function insertEmployeesToWeeklyPlanTask(Collection $data, string $taskId);
}
