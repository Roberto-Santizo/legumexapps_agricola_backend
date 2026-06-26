<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanTaskPartialClosureResource extends JsonResource
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
            'id' =>             $this->id,
            'start_date' =>     $start_date->format('Y-m-d'),
            'start_time' =>     $start_date->format('h:i:s'),
            'end_date' =>       $end_date ? $end_date->format('Y-m-d') : '',
            'end_time' =>       $end_date ? $end_date->format('h:i:s'): '',
        ];
    }
}
