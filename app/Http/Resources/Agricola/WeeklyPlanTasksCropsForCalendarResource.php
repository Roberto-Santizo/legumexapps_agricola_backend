<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanTasksCropsForCalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        $color      =               'orange';
        $task_name  =               $this->task->name;
        $lote       =               $this->cdp->lote->name ?? '';
        $cdp        =               $this->cdp ? $this->cdp->name : '';
        $title      =               $task_name . ' - ' . $lote;
        $date      =                $this->operation_date->format('Y-m-d');
        $editable   =               false;


        return [
            'id' =>                 $this->id,
            'title' =>              $title,
            'start' =>              $date,
            'backgroundColor' =>    $color,
            'editable' =>           $editable,
            'task' =>               $task_name,
            'lote' =>               $lote,
            'cdp' =>                $cdp,
            'end' =>                $date,
        ];
    }
}
