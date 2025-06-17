<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyeccion extends Model
{
    use HasFactory;
    protected $table = 'proyecciones';

    protected $fillable = [
        'evento_id',
        'mes',
        'proyeccion',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}