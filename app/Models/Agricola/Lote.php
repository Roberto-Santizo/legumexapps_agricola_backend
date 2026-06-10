<?php

namespace App\Models\Agricola;

use App\Models\Agricola\Finca;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'size', 'total_plants', 'finca_id'])]

class Lote extends Model
{
    protected $table = 'lotes';

    public function finca()
    {
        return $this->belongsTo(Finca::class, 'finca_id', 'id');
    }
}
