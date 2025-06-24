<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tablero extends Model
{
    protected $fillable = [
        'nombre_tablero',
        'evento_id',
    ];

    public function consolidados()
    {
        return $this->hasMany(Consolidado::class, 'tablero_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}