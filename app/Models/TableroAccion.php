<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableroAccion extends Model
{
    protected $fillable = ['tablero_id', 'cliente', 'servicio', 'propuesta', 'monto', 'fecha'];
}
