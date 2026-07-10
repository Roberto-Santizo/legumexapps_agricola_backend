<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['hours_per_size', 'week', 'finca_id', 'task_id', 'recipe_id', 'crop_id'])]
class TaskGuideline extends Model
{
    public function finca()
    {
        return $this->belongsTo(Finca::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
