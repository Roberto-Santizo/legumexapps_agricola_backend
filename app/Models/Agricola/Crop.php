<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'code'])]

class Crop extends Model
{
    public function inputs()
    {
        return $this->hasMany(CropInput::class);
    }

    public function parameters()
    {
        return $this->hasMany(CropParameter::class);
    }

    public function ranges()
    {
        return $this->hasMany(CropRange::class);
    }

    public function steps()
    {
        return $this->hasMany(CropStep::class);
    }
}
