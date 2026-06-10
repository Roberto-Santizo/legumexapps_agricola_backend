<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['budget', 'hours', 'workers_quantity', 'slots', 'operation_date', 'start_date', 'end_date', 'extraordinary', 'use_dron', 'prepared_insumos', 'weekly_plan_id', 'tarea_id', 'plantation_control_id', 'finca_group_id'])]

class WeeklyPlanTask extends Model
{
    protected $table = 'task_weekly_plans';

    public function weeklyPlan()
    {
        return $this->belongsTo(WeeklyPlan::class, 'weekly_plan_id', 'id');
    }
}
