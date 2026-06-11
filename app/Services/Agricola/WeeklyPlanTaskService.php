<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanTaskInsumoServiceInterface;
use App\Interfaces\Agricola\WeeklyPlanTaskServiceInterface;
use App\Models\Agricola\WeeklyPlanTask;
use Override;

class WeeklyPlanTaskService implements WeeklyPlanTaskServiceInterface
{
    public function __construct(
        private readonly WeeklyPlanTaskInsumoServiceInterface $insumoService
    ) {}

    #[Override]
    public function createWeeklyPlanTask(array $data)
    {
        $slots = $data['hours'] / 8;
        $data['workers_quantity'] = $slots;
        $data['slots'] = $slots;
        
        //CREATE TASK
        $task = WeeklyPlanTask::create($data);

        //ADD INSUMOS TO TASK
        $this->insumoService->addInsumosToTask($data['insumos'], $task);
        return $task;
    }

    #[Override]
    public function getWeeklyPlanTasks(?string $limit)
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function getWeeklyPlanTaskById(string $id)
    {
        $task = WeeklyPlanTask::find($id, ['*']);
        if (!$task) throw new NotFoundError('La tarea no existe');
        return $task;
    }

    #[Override]
    public function updateWeeklyPlanTaskById(array $data, string $id)
    {
        $task = $this->getWeeklyPlanTaskById($id);
        $task->update($data);
        return $task;
    }

    #[Override]
    public function deleteWeeklyPlanTaskById(string $id)
    {
        throw new \Exception('Not implemented');
    }
}
