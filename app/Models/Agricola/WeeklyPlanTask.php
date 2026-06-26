<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['budget', 'hours', 'workers_quantity', 'slots', 'operation_date', 'start_date', 'end_date', 'extraordinary', 'use_dron', 'prepared_insumos', 'weekly_plan_id', 'tarea_id', 'plantation_control_id', 'finca_group_id'])]

class WeeklyPlanTask extends Model
{
    protected $table = 'task_weekly_plans';

    protected $casts = [
        'operation_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

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

    public function group()
    {
        return $this->belongsTo(FincaGroup::class, 'finca_group_id', 'id');
    }

    public function supplies()
    {
        return $this->hasMany(WeeklyPlanTaskInsumo::class, 'task_weekly_plan_id', 'id');
    }

    public function employees()
    {
        return $this->hasMany(WeeklyPlanTaskEmployee::class, 'task_weekly_plan_id', 'id');
    }

    public function partialClosures()
    {
        return $this->hasMany(WeeklyPlanTaskPartialClosure::class, 'task_weekly_plan_id', 'id');
    }
}
