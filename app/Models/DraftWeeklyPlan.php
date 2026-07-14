<?php

namespace App\Models;

use App\Models\Agricola\DraftWeeklyPlanTask;
use App\Models\Agricola\Finca;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['week', 'year', 'finca_id'])]
class DraftWeeklyPlan extends Model
{
    public function finca()
    {
        return $this->belongsTo(Finca::class);
    }

    public function tasks()
    {
        return $this->hasMany(DraftWeeklyPlanTask::class);
    }

}
