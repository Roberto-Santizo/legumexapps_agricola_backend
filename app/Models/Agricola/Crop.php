<?php

namespace App\Models\Agricola;


use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];
}
