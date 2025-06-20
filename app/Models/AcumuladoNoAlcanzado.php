<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcumuladoNoAlcanzado extends Model
{
    protected $table = 'acumulado_no_alcanzado';

    protected $fillable = [
        'tablero_id',
        'cliente',
        'resultado_acumulado',
    ];
}
