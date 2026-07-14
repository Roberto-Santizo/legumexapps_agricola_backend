<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DraftWeeklyPlanTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>                         $this->id,
            'hours'=>                       $this->hours,
            'budget'=>                      $this->budget,
            'task_guideline_id'=>           $this->task_guideline_id,
            'draft_weekly_plan_id'=>        $this->draft_weekly_plan_id,
            'plantation_control_id'=>       $this->plantation_control_id,
            'task'=>                        $this->taskGuideline->task->name,
            'cdp'=>                         $this->cdp->name,
            'finca'=>                       $this->draftWeeklyPlan->finca->name,
            'lote'=>                        $this->cdp->lote->name
        ];
    }
}
