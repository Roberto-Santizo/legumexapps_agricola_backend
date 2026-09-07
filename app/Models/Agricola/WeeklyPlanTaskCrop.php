<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['plantation_control_id', 'tarea_id', 'weekly_plan_id', 'status', 'operation_date'])]

class WeeklyPlanTaskCrop extends Model
{
    public const STATUS_PENDING = 1;

    public const STATUS_IN_PROGRESS = 2;

    /**
     * Cerrada por los operarios de finca: empleados, libras e inputs ya estan
     * registrados, pero los pagos todavia no se han calculado.
     */
    public const STATUS_CLOSED = 3;

    /**
     * El motor de calculo ya genero los pagos de los empleados.
     */
    public const STATUS_CALCULATED = 4;

    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pendiente',
        self::STATUS_IN_PROGRESS => 'En Progreso',
        self::STATUS_CLOSED => 'Cerrada',
        self::STATUS_CALCULATED => 'Calculada',
    ];

    protected $table = 'task_crop_weekly_plans';

    protected $casts = [
        'operation_date' => 'datetime',
        'status' => 'integer',
    ];

    // RELATIONS

    public function weeklyPlan()
    {
        return $this->belongsTo(WeeklyPlan::class, 'weekly_plan_id', 'id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'tarea_id', 'id');
    }

    public function cdp()
    {
        return $this->belongsTo(Cdp::class, 'plantation_control_id', 'id');
    }

    public function employees()
    {
        return $this->hasMany(WeeklyPlanTaskCropEmployee::class, 'task_crop_weekly_plan_id', 'id');
    }

    public function weeklyPlanTaskCropInputs()
    {
        return $this->hasMany(WeeklyPlanTaskCropInput::class, 'task_crop_weekly_plan_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(WeeklyPlanTaskEmployeePayment::class, 'task_crop_id', 'id');
    }

    // FUNCTIONALITYS

    /**
     * El cultivo vive en el CDP; no es una relacion de Eloquent para no chocar
     * con el acceso por propiedad ($task->crop).
     */
    public function getCrop(): ?Crop
    {
        return $this->cdp?->crop;
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? 'Desconocido';
    }

    public function isClosed(): bool
    {
        return in_array($this->status, [self::STATUS_CLOSED, self::STATUS_CALCULATED], true);
    }
}
