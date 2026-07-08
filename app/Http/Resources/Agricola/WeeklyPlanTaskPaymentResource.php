<?php

namespace App\Http\Resources\Agricola;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanTaskPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>                  $this->id,
            'name'=>                $this->name,
            'code'=>                $this->code,
            'hours'=>               $this->hours,
            'amount'=>              $this->amount,
            'date'=>                $this->date->format('d-m-Y'),
            'theorical_hours'=>     $this->theorical_hours
        ];
    }
}
