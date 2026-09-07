<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'code', 'lbs', 'task_crop_weekly_plan_id'])]

class WeeklyPlanTaskCropEmployee extends Model
{
    protected $table = 'employee_task_crops';

    protected $casts = [
        'lbs' => 'float',
    ];

    public function taskCrop()
    {
        return $this->belongsTo(WeeklyPlanTaskCrop::class, 'task_crop_weekly_plan_id', 'id');
    }
}
