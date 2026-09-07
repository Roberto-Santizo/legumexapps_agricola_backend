<?php

namespace App\Services\Agricola;

use App\Actions\WeeklyPlanTasks\CalculateWeeklyPlanTaskCropPaymentsAction;
use App\Errors\BadRequestError;
use App\Errors\NotAcceptable;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanTaskCropServiceInterface;
use App\Models\Agricola\Cdp;
use App\Models\Agricola\WeeklyPlan;
use App\Models\Agricola\WeeklyPlanTaskCrop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Override;

class WeeklyPlanTaskCropService implements WeeklyPlanTaskCropServiceInterface
{
    public function __construct(
        private readonly CalculateWeeklyPlanTaskCropPaymentsAction $calculatePaymentsAction,
    ) {}

    #[Override]
    public function createWeeklyPlanTaskCrop(array $data)
    {
        $cdp = Cdp::find($data['plantation_control_id']);
        if (! $cdp) {
            throw new NotFoundError('El CDP no existe');
        }
        if (! $cdp->crop_id) {
            throw new NotAcceptable("El CDP {$cdp->name} no tiene un cultivo asignado");
        }

        $plan = WeeklyPlan::find($data['weekly_plan_id']);
        if (! $plan) {
            throw new NotFoundError('El plan semanal no existe');
        }

        $now = Carbon::now();
        $dates = collect($data['dates'])
            ->map(fn ($date) => Carbon::parse($date)->startOfDay())
            ->unique(fn (Carbon $date) => $date->toDateString())
            ->values();

        $payloads = $dates->map(fn (Carbon $date) => [
            'plantation_control_id' => $data['plantation_control_id'],
            'tarea_id' => $data['tarea_id'],
            'weekly_plan_id' => $this->getWeeklyPlanByOperationDate($date, $plan->finca_id)->id,
            'operation_date' => $date,
            'status' => WeeklyPlanTaskCrop::STATUS_PENDING,
            'created_at' => $now,
            'updated_at' => $now,
        ])->reject(fn (array $payload) => $this->taskCropExists($payload))->values();

        if ($payloads->isEmpty()) {
            throw new NotAcceptable('Las tareas de cosecha indicadas ya existen');
        }

        DB::transaction(fn () => WeeklyPlanTaskCrop::insert($payloads->all()));

        return true;
    }

    #[Override]
    public function getWeeklyPlanTasksCrop(?string $weeklyPlanId, ?Request $request = null)
    {
        if (! $weeklyPlanId) {
            throw new BadRequestError('El ID del plan es requerido');
        }

        $query = WeeklyPlanTaskCrop::query()
            ->where('weekly_plan_id', $weeklyPlanId)
            ->with(['cdp.lote', 'task', 'employees'])
            ->orderBy('id', 'DESC');

        if ($request) {
            if ($cdp = $request->query('cdp')) {
                $query->where('plantation_control_id', $cdp);
            }
            if ($task = $request->query('task')) {
                $query->where('tarea_id', $task);
            }
            if ($status = $request->query('status')) {
                $query->where('status', $status);
            }
            if ($operationDate = $request->query('operation_date')) {
                $query->whereDate('operation_date', $operationDate);
            }
        }

        return $query->get();
    }

    #[Override]
    public function getWeeklyPlanTaskCropById(string $id)
    {
        $task = WeeklyPlanTaskCrop::with([
            'cdp.lote',
            'cdp.crop',
            'task',
            'employees',
            'weeklyPlanTaskCropInputs.input',
        ])->find($id);

        if (! $task) {
            throw new NotFoundError('La tarea de cosecha no existe');
        }

        return $task;
    }

    #[Override]
    public function updateWeeklyPlanTaskCropById(array $data, string $id)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);
        $this->guardNotClosed($task);

        if (array_key_exists('operation_date', $data) && $data['operation_date']) {
            $date = Carbon::parse($data['operation_date'])->startOfDay();
            $task->loadMissing('weeklyPlan');
            if (! $task->weeklyPlan) {
                throw new NotFoundError('La tarea no tiene un plan semanal asociado');
            }

            $data['operation_date'] = $date;
            $data['weekly_plan_id'] = $this->getWeeklyPlanByOperationDate($date, $task->weeklyPlan->finca_id)->id;
        }

        $task->update($data);

        return true;
    }

    #[Override]
    public function deleteWeeklyPlanTaskCropById(string $id)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);
        $this->guardNotClosed($task);

        DB::transaction(function () use ($task) {
            $task->payments()->delete();
            $task->weeklyPlanTaskCropInputs()->delete();
            $task->employees()->delete();
            $task->delete();
        });

        return true;
    }

    #[Override]
    public function getWeeklyPlanTasksCropByCdp(string $weeklyPlanId, string $cdp)
    {
        $role = auth()->user()->role;

        $tasks = WeeklyPlanTaskCrop::where('weekly_plan_id', $weeklyPlanId)
            ->where(function ($query) use ($role) {
                if ($role != 'admin' && $role != 'adminagricola') {
                    $query->whereDate('operation_date', Carbon::today())
                        ->orWhere('status', WeeklyPlanTaskCrop::STATUS_IN_PROGRESS);
                }
            })
            ->whereHas('cdp', fn ($q) => $q->where('name', $cdp))
            ->with(['cdp.lote', 'task', 'employees'])
            ->orderBy('id', 'DESC')
            ->get();

        return $tasks;
    }

    #[Override]
    public function startWeeklyPlanTaskCrop(string $id)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);

        if ($task->status !== WeeklyPlanTaskCrop::STATUS_PENDING) {
            throw new NotAcceptable('La tarea ya fue iniciada');
        }

        if ($task->employees->count() === 0) {
            throw new NotAcceptable('La tarea no cuenta con empleados asignados');
        }

        $task->status = WeeklyPlanTaskCrop::STATUS_IN_PROGRESS;
        $task->save();

        return true;
    }

    /**
     * Cierre de los operarios de finca: guarda los inputs del motor de calculo y
     * deja la tarea lista para que los operarios administrativos generen los pagos.
     * El calculo NO corre aqui.
     */
    #[Override]
    public function closeWeeklyPlanTaskCrop(string $id, array $data)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);

        if ($task->isClosed()) {
            throw new NotAcceptable('La tarea ya fue cerrada');
        }

        if ($task->status !== WeeklyPlanTaskCrop::STATUS_IN_PROGRESS) {
            throw new NotAcceptable('La tarea no ha sido iniciada');
        }

        $this->validateTaskEmployees($task);
        $this->validateInputsBelongToCrop($task, $data['inputs']);
        $this->validateRequiredInputs($task, $data['inputs']);

        DB::transaction(function () use ($task, $data) {
            $now = Carbon::now();

            // Reemplazar los inputs para que reintentar el cierre no los duplique.
            $task->weeklyPlanTaskCropInputs()->delete();
            $task->weeklyPlanTaskCropInputs()->insert(array_map(fn ($input) => [
                'task_crop_weekly_plan_id' => $task->id,
                'crop_input_id' => $input['crop_input_id'],
                'value' => $input['value'],
                'created_at' => $now,
                'updated_at' => $now,
            ], $data['inputs']));

            $task->status = WeeklyPlanTaskCrop::STATUS_CLOSED;
            $task->save();
        });

        return true;
    }

    /**
     * Paso administrativo: corre el motor de calculo sobre una tarea ya cerrada.
     * Es idempotente, sirve tambien para recalcular una tarea ya calculada.
     */
    #[Override]
    public function calculateWeeklyPlanTaskCrop(string $id)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);

        if (! $task->isClosed()) {
            throw new NotAcceptable('La tarea aún no ha sido cerrada');
        }

        $this->calculatePaymentsAction->execute($task);

        return true;
    }

    #[Override]
    public function cleanWeeklyPlanTaskCrop(string $id)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);

        DB::transaction(function () use ($task) {
            $task->payments()->delete();
            $task->weeklyPlanTaskCropInputs()->delete();
            $task->employees()->delete();
            $task->status = WeeklyPlanTaskCrop::STATUS_PENDING;
            $task->save();
        });

        return true;
    }

    #[Override]
    public function getCropInputsForTask(string $id)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);
        $crop = $task->getCrop();

        if (! $crop) {
            throw new NotAcceptable('El CDP de la tarea no tiene un cultivo asignado');
        }

        $values = $task->weeklyPlanTaskCropInputs->keyBy('crop_input_id');

        return $crop->inputs->map(fn ($input) => [
            'task_crop_weekly_plan_id' => (int) $task->id,
            'crop_input_id' => $input->id,
            'key' => $input->key,
            'label' => $input->label,
            'required' => (bool) $input->required,
            'default_value' => $input->default_value,
            'value' => $values->get($input->id)?->value,
        ])->values();
    }

    #[Override]
    public function getWeeklyPlanTaskCropPayments(string $id)
    {
        $task = $this->getWeeklyPlanTaskCropById($id);

        return $task->payments()->select(['id', 'name', 'code', 'hours', 'amount', 'date', 'theorical_hours'])->get();
    }

    private function guardNotClosed(WeeklyPlanTaskCrop $task): void
    {
        if ($task->isClosed()) {
            throw new NotAcceptable('La tarea ya fue cerrada');
        }
    }

    private function validateTaskEmployees(WeeklyPlanTaskCrop $task): void
    {
        if ($task->employees->isEmpty()) {
            throw new NotAcceptable('La tarea no cuenta con empleados asignados');
        }

        if ($task->employees->contains(fn ($employee) => $employee->lbs === null)) {
            throw new NotAcceptable('La tarea cuenta con empleados sin libras registradas');
        }
    }

    /**
     * Los inputs obligatorios del cultivo deben venir en el cierre; el motor de
     * calculo corre despues y ya no tendria como pedirlos.
     */
    private function validateRequiredInputs(WeeklyPlanTaskCrop $task, array $inputs): void
    {
        $sent = collect($inputs)->pluck('crop_input_id');

        $missing = $task->getCrop()->inputs
            ->filter(fn ($input) => $input->required && ! $sent->contains($input->id))
            ->map(fn ($input) => $input->label)
            ->values();

        if ($missing->isNotEmpty()) {
            throw new NotAcceptable('Faltan los valores para: '.$missing->implode(', '));
        }
    }

    private function taskCropExists(array $payload): bool
    {
        return WeeklyPlanTaskCrop::where('plantation_control_id', $payload['plantation_control_id'])
            ->where('tarea_id', $payload['tarea_id'])
            ->whereDate('operation_date', $payload['operation_date'])
            ->exists();
    }

    private function validateInputsBelongToCrop(WeeklyPlanTaskCrop $task, array $inputs): void
    {
        $crop = $task->getCrop();

        if (! $crop) {
            throw new NotAcceptable('El CDP de la tarea no tiene un cultivo asignado');
        }

        $cropInputIds = $crop->inputs->pluck('id');
        $invalid = collect($inputs)->pluck('crop_input_id')->diff($cropInputIds);

        if ($invalid->isNotEmpty()) {
            throw new NotAcceptable("Los valores enviados no pertenecen al cultivo {$crop->name}");
        }
    }

    private function getWeeklyPlanByOperationDate(Carbon $operationDate, int $fincaId): WeeklyPlan
    {
        $week = $operationDate->weekOfYear;
        $year = $operationDate->year;

        $plan = WeeklyPlan::where('week', $week)
            ->where('year', $year)
            ->where('finca_id', $fincaId)
            ->first();

        if (! $plan) {
            throw new NotFoundError("No existe un plan semanal para la semana {$week} del año {$year}");
        }

        return $plan;
    }
}
