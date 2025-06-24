<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consolidado extends Model
{
    protected $table = 'consolidado';

    protected $fillable = [
        'nombre_tablero',
        'evento_id',
        'fecha',
        'descripcion_tablero',
        'feat_business_tablero',
        'cargar_a',
        'importe_tablero',
        'tablero_id',
    ];

    public function tablero()
    {
        return $this->belongsTo(Tablero::class, 'tablero_id');
    }
}
