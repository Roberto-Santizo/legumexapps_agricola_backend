<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CdpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $end_date = $this->end_date;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'start_date' => $this->start_date->format('d-m-Y'),
            'end_date' => $end_date ? $end_date->format('d-m-Y') : null,
            'total_plants' => $this->total_plants,
            'lote_id' => $this->lote_id,
            'recipe_id' => $this->recipe_id,
            'crop_id' => $this->crop_id,
            'lote' => $this->lote->name,
            'recipe' => $this->recipe->name,
            'crop' => $this->crop->name,
            'status' =>  $this->status,
            'createdAt' => $this->created_at->format('d-m-Y')
        ];
    }
}
