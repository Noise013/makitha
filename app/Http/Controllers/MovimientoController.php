<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MovimientoImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Models\Evento;


class MovimientoController extends Controller
{
    public function importar(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv',
            'evento_id' => 'required|exists:eventos,id',
        ]);
        $eventoId = $request->input('evento_id');

       

         Excel::import(new MovimientoImport($eventoId), $request->file('archivo_excel'));

         return redirect()->route('eventos.crear')->with('success', 'Archivo importado correctamente');
    }
   public function mostrarForm($evento)
   {
      return view('importar', ['evento' => $evento]);
    }



}
