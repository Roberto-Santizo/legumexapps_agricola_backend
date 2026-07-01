<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanTaskCropResource extends JsonResource
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
            'plantation_control_id' =>      $this->plantation_control_id,
            'cdp' =>                        $this->cdp->name,
            'tarea_id' =>                   $this->tarea_id,
            'task' =>                       $this->task->name,
            'weekly_plan_id' =>             $this->weekly_plan_id,
            'operation_date' =>             $this->operation_date->format('Y-m-d'),
            'status' =>                     $this->status
        ];
    }
}
