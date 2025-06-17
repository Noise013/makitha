<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MovimientoImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Models\Evento;
use App\Models\Proyeccion;


class MovimientoController extends Controller
{
    public function importar(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv',
            'evento_id' => 'required|exists:eventos,id',
            'nombre_archivo' => 'required|string|max:255',
            'mes'           => 'required|string',
            'proyeccion'    => 'required|numeric'
        ]);

        $eventoId = $request->input('evento_id');

        $evento = Evento::find($eventoId);
        $evento->nombre_archivo = $request->input('nombre_archivo');
        $evento->save();

        //importa movimientos desde el excel
        Excel::import(new MovimientoImport($eventoId), $request->file('archivo_excel'));

        Proyeccion::create([
        'evento_id'  => $eventoId,
        'mes'        => $request->input('mes'),
        'proyeccion' => $request->input('proyeccion'),
        ]);


        return redirect()->route('eventos.crear')->with('success', 'Archivo importado correctamente');
    }
   public function mostrarForm($evento)
   {
      return view('importar', ['evento' => $evento]);
    }



}
