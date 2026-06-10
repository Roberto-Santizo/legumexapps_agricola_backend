<?php

namespace App\Models\Agricola;

use App\Models\Agricola\Finca;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['week', 'year', 'finca_id'])]

class WeeklyPlan extends Model
{

    //GETTERS
    public function getBudget(): array
    {
        return [
            'total_budget' => 150,
            'used_budget' => 120
        ];
    }

    public function getExtraordinaryBudget(): array
    {
        return [
            'total_budget_ext' => 50,
            'used_total_budget_ext' => 25
        ];
    }

    public function getTasksSummary(): array
    {
        return [
            'total' => 75,
            'finished' => 30
        ];
    }


    //RELATIONS
    public function finca()
    {
        $this->belongsTo(Finca::class);
    }
}
