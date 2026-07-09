<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SummaryTasksByFincaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $summary = $this->getTasksSummary();
        $percentage = $summary['finished'] / $summary['total'];
        
        return [
            'id' =>             $this->id,
            'finca' =>          $this->finca->name,
            'total'=>           $summary['total'],
            'finished'=>        $summary['finished'],
            'percentage'=>      round($percentage*100,2)
        ];
    }
}
