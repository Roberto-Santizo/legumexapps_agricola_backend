<?php

namespace App\Interfaces\Agricola;

interface WeeklyPlanTaskCropInputServiceInterface
{
    public function createInput(array $data);

    public function getInputs(?string $taskId);

    public function getInputById(string $id);

    public function updateInputById(array $data, string $id);

    public function deleteInputById(string $id);
}
