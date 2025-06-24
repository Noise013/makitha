<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MesDinamico extends Model
{
    protected $table = 'mes_dinamico';

    protected $fillable = [
        'tablero_id',
        'cliente',
        'real',
        'plan',
    ];
}
