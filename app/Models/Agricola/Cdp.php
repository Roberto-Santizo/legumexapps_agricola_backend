<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Override;

#[Fillable(['name', 'start_date', 'end_date', 'lote_id', 'total_plants', 'recipe_id', 'crop_id'])]

class Cdp extends Model
{
    protected $table = 'plantation_controls';


    #[Override]
    protected function casts()
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime'
        ];
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    public function draftTasks()
    {
        return $this->hasMany(DraftWeeklyPlanTask::class, 'plantation_control_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(WeeklyPlanTask::class, 'plantation_control_id', 'id');
    }
}
