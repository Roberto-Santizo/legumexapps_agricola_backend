<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SummaryCdpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $draftTasks =                   $this->draftTasks;
        $tasks =                        $this->tasks()->whereNotNull('end_date')->get();
        $planned_total_budget =         $draftTasks->sum('budget');
        $planned_total_hours =          $draftTasks->sum('hours');
        $used_total_budget =            $tasks->sum('budget');
        $used_total_hours =             $tasks->sum('hours');

        return [
            'planned_total_budget' =>       round($planned_total_budget,2),
            'planned_total_hours' =>        round($planned_total_hours,2),
            'used_total_budget' =>          round($used_total_budget,2),
            'used_total_hours' =>           round($used_total_hours,2),
        ];
    }
}
