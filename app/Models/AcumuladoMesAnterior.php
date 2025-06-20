<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcumuladoMesAnterior extends Model
{
    protected $table = 'acumulado_meses_anteriores';
    
    protected $fillable = [
        'tablero_id',
        'cliente',
        'total',
    ];
}