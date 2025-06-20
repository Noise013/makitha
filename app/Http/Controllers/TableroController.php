<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ConsolidadoImport;
use App\Models\Consolidado;
use App\Models\Tablero;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Evento;
use Carbon\Carbon;
use App\Models\AcumuladoMesAnterior;
use App\Models\MesDinamico;
use App\Models\AcumuladoNoAlcanzado;
use App\Models\AccionATomar;




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

        // Importar Excel pasándole tablero_id y evento_id correctos
        Excel::import(new ConsolidadoImport($tablero->id, $eventoId), $archivo);

        return redirect()->route('tableros.datos', ['id' => $tablero->id]);
    }

    public function datos($id)
    {
        $tablero = \App\Models\Tablero::findOrFail($id);

        // Clientes únicos sacados de feat_business, tomando lo que está después del guion
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

        // Última fecha en el consolidado para el tablero
        $ultimaFecha = \App\Models\Consolidado::where('tablero_id', $id)
            ->orderByDesc('fecha')
            ->value('fecha');

        $ultimoMes = $ultimaFecha ? Carbon::parse($ultimaFecha)->translatedFormat('F Y') : 'Mes desconocido';

        // "Real" = sumatoria de importe por cliente donde cargar_a = 'FEE', agrupado correctamente espero
        $consolidado = \App\Models\Consolidado::where('tablero_id', $id)
            ->where('cargar_a', 'FEE')
            ->get();

        $reales = $consolidado->groupBy(function ($item) {
            if (preg_match('/\-\s*(.+)/', $item->feat_business, $matches)) {
                return trim($matches[1]);
            }
            return null;
        })->map(function ($group) {
            return $group->sum('importe');
        });

        $realesTotal = $consolidado->sum('importe');

        return view('creartablerodos', compact('tablero', 'clientes', 'ultimoMes', 'reales', 'realesTotal'));
    }
    
    public function guardarDatos(Request $request, $id)
    {
        $request->validate([
            'acumulado_mes_anterior' => 'required|array',
            'acumulado_mes_anterior.*.total' => 'nullable|numeric',

            'mes_dinamico' => 'required|array',
            'mes_dinamico.*.plan' => 'nullable|numeric',

            'acumulado_no_alcanzado' => 'required|array',
            'acumulado_no_alcanzado.*.resultado' => 'nullable|numeric',

            'acciones_a_tomar' => 'required|array',
            'acciones_a_tomar.*.servicio' => 'nullable|string|max:255',
            'acciones_a_tomar.*.propuesta' => 'nullable|string|max:255',
            'acciones_a_tomar.*.monto' => 'nullable|numeric',
            'acciones_a_tomar.*.fecha' => 'nullable|date',
        ]);

        // Acumulado Mes Anterior
        foreach ($request->input('acumulado_mes_anterior', []) as $cliente => $data) {
            AcumuladoMesAnterior::updateOrCreate(
                ['tablero_id' => $id, 'cliente' => $cliente],
                ['total' => $data['total']]
            );
        }

        // Mes Dinámico
        foreach ($request->input('mes_dinamico', []) as $cliente => $data) {
            $mesDinamico = MesDinamico::updateOrCreate(
                ['tablero_id' => $id, 'cliente' => $cliente],
                ['plan' => $data['plan']]
            );

            // Luego se calcula y actualiza vs_plan y porcentaje (acá medio tiré fruta, pero bueno, no quería dejarlo vacío)
             if ($mesDinamico->real !== null && $data['plan'] !== null) {
                $vsPlan = $mesDinamico->real - $data['plan'];
                $porcentaje = $data['plan'] != 0 ? ($vsPlan / $data['plan']) * 100 : null;

                $mesDinamico->update([
                    'vs_plan' => $vsPlan,
                    'porcentaje' => $porcentaje,
                ]);
            }
        }

        // Acumulado No Alcanzado
        foreach ($request->input('acumulado_no_alcanzado', []) as $cliente => $data) {
            AcumuladoNoAlcanzado::updateOrCreate(
                ['tablero_id' => $id, 'cliente' => $cliente],
                ['resultado_acumulado' => $data['resultado']]
            );
        }

        // Acciones a Tomar
        foreach ($request->input('acciones_a_tomar', []) as $cliente => $accion) {
            if (empty(array_filter($accion))) continue;

            AccionATomar::updateOrCreate(
                ['tablero_id' => $id, 'cliente' => $cliente],
                [
                    'servicio' => $accion['servicio'],
                    'propuesta' => $accion['propuesta'],
                    'monto' => $accion['monto'],
                    'fecha' => $accion['fecha'],
                ]
            );
        }

        return redirect()->route('tablero')->with('success', 'Datos guardados correctamente.');
    }

    public function index()
    {
        $tableros = \App\Models\Tablero::orderBy('created_at', 'desc')->get();
        return view('tablero', compact('tableros'));
    }

    public function detalle(Request $request)
    {
        $id = $request->query('id');
        $tablero = Tablero::findOrFail($id);

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

        $acumuladoMesAnterior = AcumuladoMesAnterior::where('tablero_id', $id)->get();
        $mesDinamico = MesDinamico::where('tablero_id', $id)->get();
        $acumuladoNoAlcanzado = AcumuladoNoAlcanzado::where('tablero_id', $id)->get();
        $accionesATomar = AccionATomar::where('tablero_id', $id)->get();

        return view('tablerodetalle', compact(
            'tablero',
            'clientes',
            'acumuladoMesAnterior',
            'mesDinamico',
            'acumuladoNoAlcanzado',
            'accionesATomar'
        ));
    }



}