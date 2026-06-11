<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $start_date = $this->start_date;
        $end_date = $this->end_date;

        return [
            'id' => $this->id,
            'budget' => $this->budget,
            'hours' => $this->hours,
            'workers_quantity' => $this->workers_quantity,
            'operation_date' => $this->operation_date->format('Y-m-d'),
            'start_date' => $start_date ? $start_date->format('Y-m-d') : null,
            'start_hour' =>  $start_date ? $start_date->format('h:m:s') : null,
            'end_date' => $end_date ? $end_date->format('Y-m-d') : null,
            'end_hour' =>  $end_date ? $end_date->format('h:m:s') : null,
            'extraordinary' => $this->extraordinary,
            'task_id' => $this->tarea_id,
            'task' => $this->task->name,
            'plantation_control_id' => $this->plantation_control_id,
            'cdp' => $this->cdp->name,
            'finca_group_id' => $this->finca_group_id,
            'group' => $this->group->name,
            'supplies' => WeeklyPlanTaskInsumoResource::collection($this->supplies)
        ];
    }
}
