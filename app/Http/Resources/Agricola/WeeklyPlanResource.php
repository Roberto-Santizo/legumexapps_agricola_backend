<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $budget = $this->getBudget();
        $extraordinaryBudget = $this->getExtraordinaryBudget();
        $tasksSummary = $this->getTasksSummary();

        return [
            'id' => $this->id,
            'year' => $this->year,
            'week' => $this->week,
            'finca' => 'Finca',
            'finca_id' => $this->finca_id,
            'created_at' => $this->created_at->format('d-m-Y'),
            'total_budget' => $budget['total_budget'],
            'used_budget' => $budget['used_budget'],
            'total_budget_ext' => $extraordinaryBudget['total_budget_ext'],
            'used_total_budget_ext' => $extraordinaryBudget['used_total_budget_ext'],
            'total_tasks' => $tasksSummary['total'],
            'finished_tasks' => $tasksSummary['finished'],
            'total_tasks_crop' => 0,
            'finished_total_tasks_crops' => 0
        ];
    }
}
