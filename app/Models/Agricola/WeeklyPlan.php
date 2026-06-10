<?php

namespace App\Models\Agricola;

use App\Models\Agricola\Finca;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['week', 'year', 'finca_id'])]

class WeeklyPlan extends Model
{

    //GETTERS
    public function getBudget(): array
    {
        $total_budget = $this->tasks->sum('budget');
        $used_budget = $this->getFinishedTasks()->get()->sum('budget');

        return [
            'total_budget' => $total_budget,
            'used_budget' => $used_budget
        ];
    }

    public function getExtraordinaryBudget(): array
    {
        $total_budget_ext = $this->tasks()->where('extraordinary', 1)->get()->sum('budget');
        $used_budget_ext  = $this->getFinishedTasks()->where('extraordinary', 1)->get()->sum('budget');

        return [
            'total_budget_ext' => $total_budget_ext,
            'used_total_budget_ext' => $used_budget_ext
        ];
    }

    public function getTasksSummary(): array
    {
        $total = $this->tasks->count();
        $finished = $this->tasks()->whereNotNull('end_date')->count();

        return [
            'total' => $total,
            'finished' => $finished
        ];
    }

    //RELATIONS
    public function getFinishedTasks()
    {
        return $this->hasMany(WeeklyPlanTask::class, 'weekly_plan_id', 'id')->whereNotNull('end_date');
    }

    public function finca()
    {
        return $this->belongsTo(Finca::class);
    }

    public function tasks()
    {
        return $this->hasMany(WeeklyPlanTask::class, 'weekly_plan_id', 'id');
    }
}
