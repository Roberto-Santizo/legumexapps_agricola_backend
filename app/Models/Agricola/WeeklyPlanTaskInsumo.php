<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['assigned_quantity', 'used_quantity', 'task_weekly_plan_id', 'insumo_id'])]

class WeeklyPlanTaskInsumo extends Model
{
    protected $table = 'task_insumos';

    public function weeklyPlanTask()
    {
        return $this->belongsTo(WeeklyPlanTask::class, 'task_weekly_plan_id', 'id');
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class, 'insumo_id', 'id');
    }
}
