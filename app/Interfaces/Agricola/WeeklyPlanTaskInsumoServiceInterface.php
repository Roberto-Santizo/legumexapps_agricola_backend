<?php

namespace App\Interfaces\Agricola;

use App\Models\Agricola\WeeklyPlanTask;

interface WeeklyPlanTaskInsumoServiceInterface
{
    public function getWeeklyPlanTaskInsumos(?string $id);
    public function addInsumosToTask(array $data, WeeklyPlanTask $task);
    public function addInsumoToTask(array $data);
    public function getInsumoById(string $id);
    public function updateInsumoById(array $data, string $id, string $role);
    public function deleteInsumoById(string $id);
}
