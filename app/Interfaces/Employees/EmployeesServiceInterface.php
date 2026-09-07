<?php

namespace App\Interfaces\Employees;

use App\Models\Agricola\WeeklyPlan;

interface EmployeesServiceInterface
{
    public function getFincaEmployees(WeeklyPlan $plan, bool $filtered = true);
    public function getWeeklyPlanEmployees(WeeklyPlan $plan);
}
