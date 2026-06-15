<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanTasksForCalendarResource extends JsonResource
{
    private $colors = [
        '1' => 'red',
        '2' => 'orange',
        '3' => 'green',
    ];


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $color      =               $this->determinateTaskColor($this);
        $task_name  =               $this->task->name;
        $lote       =               $this->cdp->lote->name ?? '';
        $cdp        =               $this->cdp ? $this->cdp->name : '';
        $title      =               $task_name . ' - ' . $lote;
        $start      =               $this->operation_date->format('Y-m-d');
        $end        =               $this->end_date ? $this->end_date->addDay()->format('Y-m-d') :  '';
        $editable   =               true;

        return [
            'id' =>                 $this->id,
            'title' =>              $title,
            'start' =>              $start,
            'backgroundColor' =>    $color,
            'editable' =>           $editable,
            'task' =>               $task_name,
            'lote' =>               $lote,
            'cdp' =>                $cdp,
            'end' =>                $end,
        ];
    }

    private function determinateTaskColor(mixed $task)
    {
        if (!$task->start_date) return $this->colors['1'];
        if ($task->start_date && !$task->end_date) return $this->colors['2'];
        if ($task->start_date && $task->end_date) return $this->colors['3'];
    }
}
