<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableroTotal extends Model
{
    protected $fillable = ['tablero_id', 'cliente', 'total'];
}
