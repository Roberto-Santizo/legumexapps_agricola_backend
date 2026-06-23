<?php

namespace App\Interfaces\Agricola;

interface WeeklyPlanTaskServiceInterface
{
    public function createWeeklyPlanTask(array $data);
    public function getWeeklyPlanTasks(?string $limit, ?string $id);
    public function getWeeklyPlanTasksByLote(?string $id);
    public function getWeeklyPlanTaskById(string $id);
    public function updateWeeklyPlanTaskById(array $data, string $id);
    public function deleteWeeklyPlanTaskById(string $id);
    public function startWeeklyPlanTask(string $id);
    public function closeWeeklyPlanTask(string $id);
    public function getWeeklyPlanTasksForCalendar(string $id);
    public function getWeeklyPlanTasksByCdp(string $weeklyPlanId, string $cdp);
}
