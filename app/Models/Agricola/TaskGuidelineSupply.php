<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['task_guideline_id', 'insumo_id', 'quantity'])]
class TaskGuidelineSupply extends Model
{
    public function taskGuideline()
    {
        return $this->belongsTo(TaskGuideline::class);
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class, 'insumo_id', 'id');
    }
}
