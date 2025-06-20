<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccionATomar extends Model
{
    protected $table = 'acciones_a_tomar';

    protected $fillable = [
        'tablero_id',
        'cliente',
        'servicio',
        'propuesta',
        'monto',
        'fecha',
    ];

    protected $dates = ['fecha'];
}
