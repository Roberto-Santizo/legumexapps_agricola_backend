<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\TaskguidelinesServiceInterface;
use App\Models\Agricola\TaskGuideline;
use Override;

class TaskGuidelinesService implements TaskguidelinesServiceInterface
{
    #[Override]
    public function createTaskGuideline(array $data)
    {
        $newTaskGuideline = TaskGuideline::create($data);
        return $newTaskGuideline;
    }

    #[Override]
    public function getTaskGuidelines(?string $limit)
    {
        $query = TaskGuideline::query();

        if($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
        
    }

    #[Override]
    public function getTaskGuidelineById(string $id)
    {
        $taskGuideline = TaskGuideline::find($id, ['*']);
        if(!$taskGuideline) throw new NotFoundError("La guía no existe");
        return $taskGuideline;
    }

    #[Override]
    public function updateTaskGuidelineById(array $data, string $id)
    {
        $task = $this->getTaskGuidelineById($id);
        $task->update($data);
        return true;
    }

    #[Override]
    public function deleteTaskGuidelineById(string $id)
    {
        $task = $this->getTaskGuidelineById($id);
        $task->delete();
        return true;
    }
}