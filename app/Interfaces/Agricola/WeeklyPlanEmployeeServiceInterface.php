<?php

namespace App\Interfaces\Agricola;

interface WeeklyPlanEmployeeServiceInterface
{
    public function createWeeklyPlanEmployee(array $data);
    public function getWeeklyPlanEmployees(string $weeklyPlanId);
    public function getWeeklyPlanEmployeeById(string $id);
    public function updateWeeklyPlanEmployeeById(array $data, string $id);
    public function deleteWeeklyPlanEmployeeById(string $id);
    public function addEmployeesToFincaGrup(array $employeesIds, string $groupId);
}
