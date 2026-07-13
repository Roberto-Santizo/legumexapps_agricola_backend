<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DraftWeeklyPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>         $this->id,
            'week'=>        $this->week,
            'year'=>        $this->year,
            'finca_id'=>    $this->finca_id,
            'finca'=>       $this->finca->name
        ];
    }
}
