<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Evento;
use App\Models\Movimiento;
use App\Imports\MovimientoImport;
use Maatwebsite\Excel\Facades\Excel;

class EventoController extends Controller
{
    public function crear()
    {
        $eventos = Evento::orderBy('created_at', 'desc')->get();
        return view('crear', compact('eventos'));
       
    }
    

    public function guardar()
    {
        do{
            $eventoId = (string) Str::random(16);
        } while (Evento::where('id',$eventoId)->exists());
    
        Evento::create(['id' => $eventoId]);

       return redirect()->route('importar.form', ['evento' => $eventoId]);

    }

    public function ver(Request $request)
    {
        $eventoId = $request->query('id'); // obtiene ID por el URL

        // Busca el evento
        $evento = Evento::find($eventoId);

        if (!$evento) {
            abort(404, 'El evento no existe');
        }

        // Busca los movimientos asociados a ese evento
        $movimientos = Movimiento::where('evento', $eventoId)->get();

        return view('eventodetalle', compact('evento', 'movimientos'));
    }
}
