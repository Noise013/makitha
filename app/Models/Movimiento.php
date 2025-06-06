<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    
    protected $table = 'movimientos';

   
    protected $fillable = [
        'fecha',
        'descripcion',
        'feat_business',
        'big_brothers',
        'g_and_a',
        'corporativo',
        'importe',
    ];

}
