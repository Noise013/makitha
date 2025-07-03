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
            'evento_id' => 'required|string', // ya no 'exists:eventos,id'
            'nombre_archivo' => 'required|string|max:255|unique:eventos,nombre_archivo',
            'proyeccion' => 'required|array',
            'proyeccion.*' => 'required|numeric',
        ], [
            'nombre_archivo.unique' => 'Ya existe un reporte con ese nombre. Por favor ingrese otro.',
        ]);

        $eventoId = $request->input('evento_id');

       //tengo que revisar que estoy haciendo mal
        $evento = Evento::find($eventoId);
        if (!$evento) {
            $evento = Evento::create(['id' => $eventoId]);
        }

        $evento->nombre_archivo = $request->input('nombre_archivo');
        $evento->save();

        
        Excel::import(new MovimientoImport($eventoId), $request->file('archivo_excel'));

        foreach ($request->input('proyeccion') as $mes => $valor) {
            Proyeccion::create([
                'evento_id' => $eventoId,
                'mes' => $mes,
                'proyeccion' => $valor,
            ]);
        }

        return redirect()->route('eventos.crear')->with('success', 'Archivo importado correctamente');
    }
    public function mostrarForm($evento)
    {
      return view('importar', ['evento' => $evento]);
    }



}
