<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MovimientoImport;
use Maatwebsite\Excel\Facades\Excel;

class MovimientoController extends Controller
{
    public function importar(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new MovimientoImport, $request->file('archivo_excel'));

        return back()->with('success', 'Archivo importado correctamente');
    }
}
