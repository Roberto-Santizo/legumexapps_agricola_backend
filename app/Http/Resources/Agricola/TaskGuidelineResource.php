<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskGuidelineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>             $this->id,
            'hours_per_size'=>  $this->hours_per_size,
            'week'=>            $this->week,
            'finca_id'=>        $this->finca_id,
            'finca'=>           $this->finca->name,
            'task_id'=>         $this->task_id,
            'task'=>            $this->task->name,
            'recipe_id'=>       $this->recipe_id,
            'recipe'=>          $this->recipe->name,
            'crop_id'=>         $this->crop_id,
            'crop'=>            $this->crop->name
        ];
    }
}
