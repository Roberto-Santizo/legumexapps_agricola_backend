<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['code', 'name', 'weekly_plan_id', 'finca_group_id'])]

class WeeklyPlanEmployee extends Model
{
    protected $table = 'weekly_assignment_employees';

    public function group()
    {
        return $this->belongsTo(FincaGroup::class, 'finca_group_id', 'id');
    }
}
