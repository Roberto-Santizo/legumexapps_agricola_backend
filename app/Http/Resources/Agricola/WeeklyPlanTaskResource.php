<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
        $operation_date = $this->operation_date;
        $isFinished = $start_date && $end_date;
        $isInProgress = $start_date && !$end_date;
        $total_hours = $isFinished ? round($start_date->diffInHours($end_date), 2) : ($isInProgress ? round($start_date->diffInHours(Carbon::now())) : 0);
        $isPaused = $this->partialClosures()->where('end_date', null)->first();
        $status = $isFinished ? 4 : ($isPaused ? 3 : ($isInProgress ? 2 : 1));

        return [
            'id' =>                         $this->id,
            'budget' =>                     $this->budget,
            'hours' =>                      $this->hours,
            'workers_quantity' =>           $this->workers_quantity,
            'extraordinary' =>              $this->extraordinary,
            'weekly_plan_id' =>             $this->weekly_plan_id,
            'task_id' =>                    $this->tarea_id,
            'task' =>                       $this->task->name,
            'plantation_control_id' =>      $this->plantation_control_id,
            'cdp' =>                        $this->cdp->name,
            'finca_group_id' =>             $this->finca_group_id,
            'group' =>                      $this->group ? $this->group->code : 'Sin grupo asociado',
            'use_dron' =>                   $this->use_dron,
            'total_hours' =>                $total_hours * $this->employees()->count(),
            'operation_date' =>             $operation_date ? $this->operation_date->format('Y-m-d') : null,
            'start_date' =>                 $start_date ? $start_date->format('Y-m-d') : null,
            'start_hour' =>                 $start_date ? $start_date->format('h:i:s') : null,
            'end_date' =>                   $end_date ? $end_date->format('Y-m-d') : null,
            'end_hour' =>                   $end_date ? $end_date->format('h:i:s') : null,
            'status' =>                     $status,
        ];
    }
}
