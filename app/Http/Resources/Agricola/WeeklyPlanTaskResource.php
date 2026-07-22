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
        $totalEmployees = $this->employees()->count();
        $operation_date = $this->operation_date;
        $isFinished = $start_date && $end_date;
        $isInProgress = $start_date && !$end_date;
        $total_hours = $isFinished ? round($start_date->diffInHours($end_date), 2) : ($isInProgress ? round($start_date->diffInHours(Carbon::now())) : 0);
        $isPaused = $this->partialClosures()->where('end_date', null)->first();
        $status = $isFinished ? 4 : ($isPaused ? 3 : ($isInProgress ? 2 : 1));
        $partialClosures = $this->partialClosures()->whereNotNull('end_date')->get();
        $partialClosureHours = $this->getPartialClosuresHours($partialClosures);
        
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
            'total_hours' =>                ($total_hours * $totalEmployees) - ($partialClosureHours * $totalEmployees),
            'operation_date' =>             $operation_date ? $this->operation_date->format('Y-m-d') : null,
            'start_date' =>                 $start_date ? $start_date->format('Y-m-d') : null,
            'start_date_label' =>           $start_date ? $start_date->format('d-m-Y') : null,
            'start_hour' =>                 $start_date ? $start_date->format('H:i:s') : null,
            'start_hour_label' =>           $start_date ? $start_date->format('h:i:s A') : null,
            'end_date' =>                   $end_date ? $end_date->format('Y-m-d') : null,
            'end_date_label' =>             $end_date ? $end_date->format('d-m-Y') : null,
            'end_hour' =>                   $end_date ? $end_date->format('H:i:s') : null,
            'end_hour_label' =>             $end_date ? $end_date->format('h:i:s A') : null,
            'status' =>                     $status,
        ];
    }

    private function getPartialClosuresHours(mixed $partialClosures)
    {
        $total_hours = 0;

        $partialClosures->map(function($closure) use(&$total_hours) {
            $closureHours = $closure->start_date->diffInHours($closure->end_date);
            $total_hours += $closureHours;
        });

        return $total_hours;    
    }
}
