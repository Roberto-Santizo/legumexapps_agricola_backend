<?php

namespace App\Models\Agricola;

use App\Models\Lote;
use Illuminate\Database\Eloquent\Model;

class Finca extends Model
{
    protected $fillable = [
        'name',
        'code',
        'terminal_id'
    ];


    public function lotes()
    {
        $this->hasMany(Lote::class);
    }
}
