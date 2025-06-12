<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    public $incrementing = false; // el id no es autoincremental
    protected $keyType = 'string';

    protected $fillable = ['id'];
    
}
