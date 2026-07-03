<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanTaskCropInputResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>                             $this->id,
            'label' =>                          $this->input->label,
            'task_crop_weekly_plan_id' =>       $this->task_crop_weekly_plan_id,
            'crop_input_id' =>                  $this->crop_input_id,
            'value' =>                          $this->value,
        ];
    }
}
