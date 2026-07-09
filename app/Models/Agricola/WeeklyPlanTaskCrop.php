<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['plantation_control_id', 'tarea_id', 'weekly_plan_id', 'status', 'operation_date'])]

class WeeklyPlanTaskCrop extends Model
{
    protected $table = 'task_crop_weekly_plans';

    protected $casts = [
        'operation_date' => 'datetime',
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
}
