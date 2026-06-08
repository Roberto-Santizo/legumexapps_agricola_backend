<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Model;

class Cdp extends Model
{
    protected $table = 'plantation_controls';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'lote_id',
        'total_plants',
        'recipe_id',
        'crop_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

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
}
