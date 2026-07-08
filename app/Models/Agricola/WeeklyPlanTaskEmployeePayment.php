<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'code', 'hours', 'amount', 'task_plan_id', 'task_crop_id', 'weekly_plan_id', 'date', 'theorical_hours'])]
class WeeklyPlanTaskEmployeePayment extends Model
{
    protected $table = 'employee_payment_weekly_summaries';

    protected $casts = [
        'date' => 'datetime'
    ];

    public function weeklyPlan()
    {
        return $this->belongsTo(WeeklyPlan::class, 'weekly_plan_id', 'id');
    }

    public function weeklyPlanTask()
    {
        return $this->belongsTo(WeeklyPlanTask::class, 'task_plan_id', 'id');
    }

    public function weeklyPlanTaskCrop()
    {
        return $this->belongsTo(WeeklyPlanTaskCrop::class, 'task_crop_id', 'id');
    }
}
