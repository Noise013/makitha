<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyeccion extends Model
{
    protected $table = 'proyecciones';

    protected $fillable = [
        'mes',
        'proyeccion',
        'evento_id',
    ];
}