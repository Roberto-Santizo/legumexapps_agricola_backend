<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskGuidelineSupplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>                 $this->id,
            'task_guideline_id'=>   $this->task_guideline_id,
            'insumo_id'=>           $this->insumo_id,
            'quantity'=>            $this->quantity,
            'supply' =>             new SupplyResource($this->supply)
        ];
    }
}
