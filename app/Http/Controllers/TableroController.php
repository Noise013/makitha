<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableroController extends Controller
{
    public function crear()
    {
        return view('creartablero'); // la vista no está completa
    }

    public function guardar(Request $request)
    {
        // Por ahora solo redirecciona
        return redirect()->route('tablero')->with('success', 'Tablero creado correctamente.');
    }
}
