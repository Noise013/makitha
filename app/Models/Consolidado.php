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
        'descripcion',
        'feat_business',
        'cargar_a',
        'importe',
        'tablero_id',
    ];

    public function tablero()
    {
        return $this->belongsTo(Tablero::class, 'tablero_id');
    }
}
