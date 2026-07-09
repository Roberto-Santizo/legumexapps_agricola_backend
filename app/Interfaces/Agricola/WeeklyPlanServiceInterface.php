<?php

namespace App\Interfaces\Agricola;

interface WeeklyPlanServiceInterface
{
    public function createWeeklyPlan(array $data);
    public function getWeeklyPlans(?string $limit, mixed $user);
    public function getWeeklyPlanById(string $id);
    public function getWeeklyPlanByParams(array $params);
    public function updateWeeklyPlan(array $data, string $id);
    public function uploadTasksToWeeklyPlan(mixed $file, string $id);
}
