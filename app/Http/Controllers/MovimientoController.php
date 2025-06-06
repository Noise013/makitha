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
        ]);
        $eventoId = (string) Str::random(16); //hash

        //registro en la tabla eventos
        Evento::create(['id' => $eventoId]);

         Excel::import(new MovimientoImport($eventoId), $request->file('archivo_excel'));

        return redirect()->back()->with('success', 'Archivo importado correctamente');
    }
}
