<?php

namespace App\Models\Agricola;

use App\Models\DraftWeeklyPlan;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['hours', 'budget', 'task_guideline_id', 'draft_weekly_plan_id', 'plantation_control_id'])]
class DraftWeeklyPlanTask extends Model
{
    public function taskGuideline()
    {
        return $this->belongsTo(TaskGuideline::class, 'task_guideline_id', 'id');
    }

    public function draftWeeklyPlan()
    {
        return $this->belongsTo(DraftWeeklyPlan::class,);
    }

    public function cdp()
    {
        return $this->belongsTo(Cdp::class, 'plantation_control_id', 'id');
    }
}
