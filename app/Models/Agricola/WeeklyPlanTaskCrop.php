<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['plantation_control_id', 'tarea_id', 'weekly_plan_id', 'status'])]

class WeeklyPlanTaskCrop extends Model
{
    protected $table = 'task_crop_weekly_plans';
}
