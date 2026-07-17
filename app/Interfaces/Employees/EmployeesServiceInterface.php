<?php

namespace App\Interfaces\Employees;

use App\Models\Agricola\WeeklyPlan;

interface EmployeesServiceInterface
{
    public function getFincaEmployees(WeeklyPlan $plan);
    public function getWeeklyPlanEmployees(WeeklyPlan $plan);
}
