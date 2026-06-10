<?php

namespace App\Interfaces\Agricola;

interface WeeklyPlanServiceInterface
{
    public function createWeeklyPlan(array $data);
    public function getWeeklyPlans(?string $limit);
    public function getWeeklyPlanById(string $id);
    public function getWeeklyPlanByParams(array $params);
    public function updateWeeklyPlan(array $data, string $id);
}
