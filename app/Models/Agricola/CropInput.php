<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['crop_id', 'key', 'label', 'required', 'default_value'])]

class CropInput extends Model
{
    //
}
