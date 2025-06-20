<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableroPlan extends Model
{
    protected $fillable = ['tablero_id', 'cliente', 'plan'];
}
