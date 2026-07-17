<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanTasksByLoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->formatData();
        return $data;
    }

    public function formatData()
    {
        $data = [];
        $tasks = $this->tasks()->with('cdp.lote')->get();
        $tasksGroupByLote = $tasks->groupBy('cdp.name');

        foreach ($tasksGroupByLote as $key => $tasks) {
            $budget = round($tasks->sum('budget'), 2);
            $total_hours = round($tasks->sum('hours'), 2);
            $total_employees = $tasks->sum('workers_quantity');
            $total_tasks = $tasks->count();
            $lote_name = $tasks->first()->cdp->lote->name;

            $data[] = [
                'cdp' => $key,
                'lote' => $lote_name,
                'total_budget' => $budget,
                'total_employees' => $total_employees,
                'total_tasks' => $total_tasks,
                'total_hours' => $total_hours
            ];
        }

        return $data;
    }
}
