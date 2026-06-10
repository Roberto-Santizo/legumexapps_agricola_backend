<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'code', 'terminal_id'])]

class Finca extends Model
{
    public function lotes()
    {
        $this->hasMany(Lote::class);
    }
}
