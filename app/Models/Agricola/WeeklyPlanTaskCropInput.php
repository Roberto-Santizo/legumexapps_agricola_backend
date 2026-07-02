<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['task_crop_weekly_plan_id', 'crop_input_id', 'value'])]

class WeeklyPlanTaskCropInput extends Model
{
    protected $table = 'task_crop_weekly_plan_inputs';

    public function input()
    {
        return $this->belongsTo(CropInput::class, 'crop_input_id', 'id');
    }

    public function taskCrop()
    {
        return $this->belongsTo(WeeklyPlanTaskCrop::class, 'task_crop_weekly_plan_id', 'id');
    }
}
