<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanTaskInsumoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assigned_quantity' => $this->assigned_quantity,
            'used_quantity' => $this->used_quantity,
            'insumo_id' => $this->insumo_id,
            'insumo' => $this->supply->name
        ];
    }
}
