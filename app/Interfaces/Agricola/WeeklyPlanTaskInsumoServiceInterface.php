<?php

namespace App\Interfaces\Agricola;

use App\Models\Agricola\WeeklyPlanTask;

interface WeeklyPlanTaskInsumoServiceInterface
{
    public function addInsumosToTask(array $data, WeeklyPlanTask $task);
    public function getInsumoById(string $id);
    public function updateInsumoById(array $data, string $id);
    public function deleteInsumoById(string $id);
}
