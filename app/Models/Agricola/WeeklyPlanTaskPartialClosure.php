<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['start_date', 'end_date', 'task_weekly_plan_id'])]
class WeeklyPlanTaskPartialClosure extends Model
{
    protected $table = 'partial_closures';
 
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];


    public function weeklyPlanTask()
    {
        return $this->belongsTo(WeeklyPlanTask::class, 'task_weekly_plan_id', 'id');
    }
}
