<?php

namespace App\Models\Agricola;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tareas';

    protected $fillable = [
        'name',
        'code',
        'description'
    ];
}
