<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotAcceptable;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
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
        private readonly WeeklyPlanServiceInterface $weeklyPlanService,
    ) {}

    #[Override]
    public function createWeeklyPlanTask(array $data)
    {
        $slots = $data['hours'] / 8;
        $data['workers_quantity'] = $slots;
        $data['slots'] = $slots;

        $task = WeeklyPlanTask::create($data);

        $this->insumoService->addInsumosToTask($data['insumos'], $task);
        return $task;
    }

    #[Override]
    public function getWeeklyPlanTasks(?string $limit, ?string $id)
    {
        if (!$id) throw new BadRequestError("El ID del plan es requerido");
        $tasks = WeeklyPlanTask::where('weekly_plan_id', '=', $id, null)->get();
        return $tasks;
    }

    #[Override]
    public function getWeeklyPlanTasksByLote(?string $id)
    {
        if (!$id) throw new BadRequestError("El ID del plan es requerido");
        $plan = $this->weeklyPlanService->getWeeklyPlanById($id);
        return $plan;
    }

    #[Override]
    public function getWeeklyPlanTaskById(string $id)
    {
        $task = WeeklyPlanTask::find($id, ['*']);
        if (!$task) throw new NotFoundError("La tarea no existe");
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

    #[Override]
    public function closeWeeklyPlanTask(string $id)
    {
        $task = $this->getWeeklyPlanTaskById($id);
        $taskSupplies = $task->supplies()->where('task_weekly_plan_id', '=', $id)->where('used_quantity', '=', null)->get();
        if ($taskSupplies->count() > 0) throw new NotAcceptable("La tarea cuenta con insumos sin cerrar");
        if ($task->end_date) throw new BadRequestError("La tarea ya fue cerrada");


        $task->end_date = Carbon::now();
        $task->save();

        return true;
    }

    #[Override]
    public function getWeeklyPlanTasksForCalendar(string $id)
    {
        $tasks = $this->getWeeklyPlanTasks(null, $id);
        $filterdTasks = $tasks->filter(function ($task) {
            return $task->operation_date != null;
        });

        return $filterdTasks;
    }

    #[Override]
    public function getWeeklyPlanTasksByCdp(string $weeklyPlanId, string $cdp)
    {
        $tasks = WeeklyPlanTask::where('weekly_plan_id', $weeklyPlanId)
            ->whereHas('cdp', fn($q) => $q->where('name', $cdp))
            ->with('cdp')
            ->get();

        return $tasks;
    }
}
