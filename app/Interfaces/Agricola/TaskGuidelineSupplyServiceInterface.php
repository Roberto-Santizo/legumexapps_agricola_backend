<?php

namespace App\Interfaces\Agricola;

interface TaskGuidelineSupplyServiceInterface
{
    public function createTaskGuidelineSupply(array $data);
    public function getTaskGuidelineSupplies(?string $taskGuidelineId);
    public function getTaskGuidelineSupplyById(string $id);
    public function updateTaskGuidelineSupplyById(array $data, string $id);
    public function deleteTaskGuidelineSupplyById(string $id);
}
