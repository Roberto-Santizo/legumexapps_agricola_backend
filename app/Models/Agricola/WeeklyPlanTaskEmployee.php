<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['name', 'code', 'task_weekly_plan_id'])]

class WeeklyPlanTaskEmployee extends Model
{
    protected $table = 'employee_tasks';
}
