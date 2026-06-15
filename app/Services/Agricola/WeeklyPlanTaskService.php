<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanTaskInsumoServiceInterface;
use App\Interfaces\Agricola\WeeklyPlanTaskServiceInterface;
use App\Models\Agricola\WeeklyPlanTask;
use Carbon\Carbon;
use Override;

class WeeklyPlanTaskService implements WeeklyPlanTaskServiceInterface
{
    public function __construct(
        private readonly WeeklyPlanTaskInsumoServiceInterface $insumoService,
        private readonly WeeklyPlanTaskEmployeeService $employeeService,
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

    #[Override]
    public function startWeeklyPlanTask(string $id)
    {
        $task = $this->getWeeklyPlanTaskById($id);
        if (!$task->group && !$task->use_dron) throw new BadRequestError("La tarea no cuenta con un grupo asignado");
        $employees = $task->group->employees()->where('weekly_plan_id', $task->weekly_plan_id)->get();

        $this->employeeService->insertEmployeesToWeeklyPlanTask($employees, $task->id);

        $task->start_date = Carbon::now();
        $task->save();
        return $task;
    }
}
