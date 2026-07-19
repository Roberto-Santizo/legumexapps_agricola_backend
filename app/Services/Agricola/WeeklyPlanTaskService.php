<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotAcceptable;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
use App\Interfaces\Agricola\WeeklyPlanTaskInsumoServiceInterface;
use App\Interfaces\Agricola\WeeklyPlanTaskServiceInterface;
use App\Models\Agricola\WeeklyPlanEmployee;
use App\Models\Agricola\WeeklyPlanTask;
use Illuminate\Http\Request;
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
    public function getWeeklyPlanTasks(?string $limit, ?string $id, Request $request)
    {
        $task = $request->query('task');
        $group = $request->query('group');
        $cdp = $request->query('cdp');
        $use_dron = $request->query('use_dron');
        $operation_date = $request->query('operation_date');

        if (!$id) throw new BadRequestError("El ID del plan es requerido");
        $query = WeeklyPlanTask::query();
        $query->where('weekly_plan_id', $id);

        if ($task) $query->where('tarea_id', $task);
        if ($group) $query->where('finca_group_id', $group);
        if ($cdp) $query->where('plantation_control_id', $cdp);
        if ($use_dron) $query->where('use_dron', $use_dron);
        if ($operation_date) $query->whereDate('operation_date', $operation_date);



        return $query->get();
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
        $task = WeeklyPlanTask::where('id', $id)->first();
        if (!$task) throw new NotFoundError("La tarea semanal no existe");
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
        $task = $this->getWeeklyPlanTaskById($id);
        $task->employees()->delete();
        $task->partialClosures()->delete();
        $task->supplies()->delete();
        $task->delete();
        return true;
    }

    #[Override]
    public function startWeeklyPlanTask(string $id)
    {
        $task = $this->getWeeklyPlanTaskById($id);
        $employees = WeeklyPlanEmployee::where('weekly_plan_id', $task->weekly_plan_id)->where('finca_group_id', $task->finca_group_id)->get();
        if (!$task->group && !$task->use_dron) throw new BadRequestError("La tarea no cuenta con un grupo asignado");
        if($employees->count() == 0 && !$task->use_dron) throw new BadRequestError("El grupo no cuenta con empleados asignados");
        
        if(!$task->use_dron){
            $employees = $task->group->employees()->where('weekly_plan_id', $task->weekly_plan_id)->get();
            $this->employeeService->insertEmployeesToWeeklyPlanTask($employees, $task->id);
        }


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

        return $task;
    }

    #[Override]
    public function cleanWeeklyPlanTask(string $id)
    {
        $task = $this->getWeeklyPlanTaskById($id);
        $task->employees()->delete();
        $task->partialClosures()->delete();
        $task->start_date = null;
        $task->end_date = null;
        $task->save();
        return true;
    }

    #[Override]
    public function getWeeklyPlanTasksForCalendar(string $id)
    {
        if (!$id) throw new BadRequestError("El ID del plan es requerido");
        $query = WeeklyPlanTask::query();
        $query->where('weekly_plan_id', $id);

        $tasks = $query->get();

        $filterdTasks = $tasks->filter(function ($task) {
            return $task->operation_date != null;
        });

        return $filterdTasks;
    }

    #[Override]
    public function getWeeklyPlanTasksByCdp(string $weeklyPlanId, string $cdp)
    {
        $tasks = WeeklyPlanTask::where('weekly_plan_id', $weeklyPlanId)
            ->where(function ($query) {
                $query->whereDate('operation_date', Carbon::today())
                    ->orWhereNotNull('start_date')->whereNull('end_date');
            })
            ->whereHas('cdp', fn ($q) => $q->where('name', $cdp))
            ->with('cdp')
            ->get();

        return $tasks;
    }

    #[Override]
    public function getWeeklyPlanTaskPayments(string $id)
    {
        $task = $this->getWeeklyPlanTaskById($id);
        return $task->payments()->select(['id', 'name', 'code', 'hours', 'amount', 'date', 'theorical_hours'])->get();
    }

    #[Override]
    public function assignOperationDateToTasks(array $data)
    {
        WeeklyPlanTask::whereIn('id', $data['tasks'])->update(['operation_date' => $data['operation_date'], 'finca_group_id' => $data['finca_group_id']]);

        return true;
    }
}
