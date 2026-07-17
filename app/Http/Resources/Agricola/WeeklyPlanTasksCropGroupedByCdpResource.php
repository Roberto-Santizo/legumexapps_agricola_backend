<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanTasksCropGroupedByCdpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];
        $tasks = $this->tasksCrops()->with('cdp.lote')->get();
        $tasksGroupByCdp = $tasks->groupBy('cdp.name');

        foreach ($tasksGroupByCdp as $key => $tasks) {
            $total_tasks = $tasks->count();
            $lote_name = $tasks->first()->cdp->lote->name;

            $data[] = [
                'cdp' => $key,
                'lote' => $lote_name,
                'total_tasks' => $total_tasks,
            ];
        }

        return $data;
    }
}
