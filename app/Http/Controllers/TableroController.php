<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ConsolidadoImport;
use App\Models\Consolidado;
use App\Models\Tablero;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Evento;
use Carbon\Carbon;
use App\Models\TableroTotal;
use App\Models\TableroPlan;
use App\Models\TableroAccion;



class TableroController extends Controller
{
    public function crear()
    {
        $eventos = Evento::all(); // agarra todos los eventos
        return view('creartablero', compact('eventos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre_tablero' => 'required|string|max:255',
            'archivo_consolidado' => 'required|file|mimes:xlsx,xls,csv',
            'evento_id' => 'required|exists:eventos,id',
        ]);

        $nombreTablero = $request->input('nombre_tablero');
        $eventoId = $request->input('evento_id');
        $archivo = $request->file('archivo_consolidado');

        // Crear tablero
        $tablero = Tablero::create([
            'nombre_tablero' => $nombreTablero,
            'evento_id' => $eventoId,
        ]);

        // Importar con tablero_id
        Excel::import(new ConsolidadoImport($tablero->id, $nombreTablero, $eventoId), $archivo);

        return redirect()->route('tableros.datos', ['id' => $tablero->id]);
    }

    public function datos($id)
    {
        $tablero = \App\Models\Tablero::findOrFail($id);

        // Clientes únicos
        $clientes = \App\Models\Consolidado::where('tablero_id', $id)
            ->select('feat_business')
            ->distinct()
            ->get()
            ->map(function ($item) {
                if (preg_match('/\-\s*(.+)/', $item->feat_business, $matches)) {
                    return trim($matches[1]);
                }
                return null;
            })
            ->filter()
            ->unique()
            ->values();

        // Última fecha en el consolidado para este tablero
        $ultimaFecha = \App\Models\Consolidado::where('tablero_id', $id)
            ->orderByDesc('fecha')
            ->value('fecha');

        $ultimoMes = $ultimaFecha ? Carbon::parse($ultimaFecha)->translatedFormat('F Y') : 'Mes desconocido';

        // "Real" = sumatoria de importe por cliente donde "CARGAR A" = 'FEE'
        $reales = \App\Models\Consolidado::where('tablero_id', $id)
            ->where('cargar_a', 'FEE')
            ->get()
            ->groupBy(function ($item) {
                if (preg_match('/\-\s*(.+)/', $item->feat_business, $matches)) {
                    return trim($matches[1]);
                }
                return null;
            })
            ->map(function ($group) {
                return $group->sum('importe');
            });

        $realesTotal = \App\Models\Consolidado::where('tablero_id', $id)
            ->where('cargar_a', 'FEE')
            ->sum('importe');


        return view('creartablerodos', compact('tablero', 'clientes', 'ultimoMes', 'reales', 'realesTotal'));
    }
    
    public function guardarDatos(Request $request, $id)
    {
        $request->validate([
            'totales' => 'required|array',
            'totales.*' => 'nullable|numeric',

            'plan' => 'required|array',
            'plan.*' => 'nullable|numeric',

            'acciones' => 'required|array',
            'acciones.*.servicio' => 'nullable|string|max:255',
            'acciones.*.propuesta' => 'nullable|string|max:255',
            'acciones.*.monto' => 'nullable|numeric',
            'acciones.*.fecha' => 'nullable|date',
        ]);

        foreach ($request->input('totales', []) as $cliente => $valor) {
            TableroTotal::updateOrCreate(
                ['tablero_id' => $id, 'cliente' => $cliente],
                ['total' => $valor]
            );
        }

        foreach ($request->input('plan', []) as $cliente => $valor) {
            TableroPlan::updateOrCreate(
                ['tablero_id' => $id, 'cliente' => $cliente],
                ['plan' => $valor]
            );
        }

        foreach ($request->input('acciones', []) as $cliente => $accion) {
            if (empty(array_filter($accion))) continue;

            TableroAccion::updateOrCreate(
                ['tablero_id' => $id, 'cliente' => $cliente],
                [
                    'servicio' => $accion['servicio'],
                    'propuesta' => $accion['propuesta'],
                    'monto' => $accion['monto'],
                    'fecha' => $accion['fecha'],
                ]
            );
        }

        return redirect()->route('tableros.crear')->with('success', 'Datos guardados correctamente.');
    }

    public function index()
    {
        $tableros = \App\Models\Tablero::orderBy('created_at', 'desc')->get();
        return view('tablero', compact('tableros'));
    }



}
