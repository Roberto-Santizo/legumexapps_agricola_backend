<?php

namespace App\Models;

use App\Models\Agricola\Finca;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table = 'lotes';

    protected $fillable = [
        'name',
        'size',
        'total_plants',
        'finca_id'
    ];

    public function finca()
    {
        return $this->belongsTo(Finca::class, 'finca_id', 'id');
    }
}
