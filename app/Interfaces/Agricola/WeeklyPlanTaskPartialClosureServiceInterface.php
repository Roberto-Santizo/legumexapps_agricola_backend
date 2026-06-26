<?php

namespace App\Interfaces\Agricola;

interface WeeklyPlanTaskPartialClosureServiceInterface
{
    public function addOrEditPartialClosure(array $data);
    public function createPartialClosure(array $data);
    public function getPartialClosures(?string $taskId);
    public function getPartialClosureById(string $id);
    public function updatePartialClosureById(string $id, array $data);
    public function deletePartialClosureById(string $id);
}
