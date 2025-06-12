<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Evento;
use App\Imports\MovimientoImport;
use Maatwebsite\Excel\Facades\Excel;

class EventoController extends Controller
{
    public function crear()
    {
        return view('crear');
    }
    

    public function guardar()
    {
        do{
            $eventoId = (string) Str::random(16);
        } while (Evento::where('id',$eventoId)->exists());
    
        Evento::create(['id' => $eventoId]);

       return redirect()->route('importar.form', ['evento' => $eventoId]);

    }
}
