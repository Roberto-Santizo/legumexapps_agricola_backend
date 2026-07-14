<?php

namespace App\Actions\DraftWeeklyPlan;

use App\Errors\BadRequestError;
use App\Models\Agricola\WeeklyPlan;
use App\Models\Agricola\WeeklyPlanTask;
use App\Models\DraftWeeklyPlan;

class ConfirmDraftWeeklyPlan
{
    public function exec(DraftWeeklyPlan $draftWeeklyPlan): void
    {
        $plan = $this->getOrCreateWeeklyPlan($draftWeeklyPlan);
        $tasks = $draftWeeklyPlan->tasks()->with(['cdp:id', 'taskGuideline.task:id'])->get();

        $now = now();
        $rows = $tasks->map(function ($task) use ($plan, $now) {
            $slots = $task->hours / 8;

            return [
                'weekly_plan_id' => $plan->id,
                'plantation_control_id' => $task->cdp->id,
                'hours' => $task->hours,
                'budget' => $task->budget,
                'tarea_id' => $task->taskGuideline->task->id,
                'slots' => $slots,
                'workers_quantity' => $slots,
                'use_dron' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        if ($rows !== []) {
            WeeklyPlanTask::insert($rows);
        }
    }

    private function getOrCreateWeeklyPlan(DraftWeeklyPlan $draftWeeklyPlan)
    {
        $existsPlan = WeeklyPlan::where('finca_id',$draftWeeklyPlan->finca_id)->where('week', $draftWeeklyPlan->week)->where('year', $draftWeeklyPlan->year)->first();
        if($existsPlan) throw new BadRequestError("Ya existe un plan con la información del draft");

        return WeeklyPlan::create([
            'finca_id' => $draftWeeklyPlan->finca_id,
            'week'=>      $draftWeeklyPlan->week,
            'year'=>      $draftWeeklyPlan->year
        ]);
    }
}
