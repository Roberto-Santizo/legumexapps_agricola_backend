<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\TaskGuidelineSupplyServiceInterface;
use App\Models\Agricola\TaskGuidelineSupply;
use Override;

class TaskGuidelineSupplyService implements TaskGuidelineSupplyServiceInterface
{
    #[Override]
    public function createTaskGuidelineSupply(array $data)
    {
        $result = TaskGuidelineSupply::create($data);
        return $result;
    }

    #[Override]
    public function getTaskGuidelineSupplies(?string $taskGuidelineId)
    {
        if(!$taskGuidelineId) throw new BadRequestError("El ID de la tarea guía es requerido");
        $supplies = TaskGuidelineSupply::where('task_guideline_id', '=' ,$taskGuidelineId)->get();
        return $supplies;
    }

    #[Override]
    public function getTaskGuidelineSupplyById(string $id)
    {
        $taskGuidelineSupply = TaskGuidelineSupply::find($id);
        if(!$taskGuidelineSupply) throw new NotFoundError("La receta no existe");
        return $taskGuidelineSupply;
        
    }

    #[Override]
    public function updateTaskGuidelineSupplyById(array $data, string $id)
    {
        $taskGuidelineSupply = $this->getTaskGuidelineSupplyById($id);
        $taskGuidelineSupply->update($data);
        return true;
    }

    #[Override]
    public function deleteTaskGuidelineSupplyById(string $id)
    {
        $taskGuidelineSupply = $this->getTaskGuidelineSupplyById($id);
        $taskGuidelineSupply->delete();
        return true;
    }
}
