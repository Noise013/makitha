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
use Illuminate\Support\Str;



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
            'nombre_tablero' => 'required|string|max:255|unique:tableros,nombre_tablero',
            'archivo_consolidado' => 'required|file|mimes:xlsx,xls,csv',
            'evento_id' => 'required|exists:eventos,id',
        ], [
            'nombre_tablero.unique' => 'Ya existe un tablero con ese nombre. Por favor ingrese otro.',
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
        Excel::import(new ConsolidadoImport($tablero->id, $eventoId, $nombreTablero), $archivo);

        return redirect()->route('tableros.datos', ['id' => $tablero->id]);
    }

    public function datos($id)
    {
        $tablero = \App\Models\Tablero::findOrFail($id);
        $reporte = Evento::find($tablero->evento_id);
        

        // Clientes únicos sacados de feat_business, tomando lo que está después del guion
        $clientes = \App\Models\Consolidado::where('tablero_id', $id)
            ->select('feat_business_tablero')
            ->distinct()
            ->get()
            ->map(function ($item) {
                if (preg_match('/\-\s*(.+)/', $item->feat_business_tablero, $matches)) {
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
        $reales = collect();

        foreach ($clientes as $cliente) {
            $total = Consolidado::where('tablero_id', $id)
                ->get()
                ->filter(function ($item) use ($cliente) {
                    // Extraer partes de feat_business_tablero
                    if (preg_match('/(.+?)\s*-\s*(.+)/', $item->feat_business_tablero, $matches)) {
                        $parteAntesDelGuion = trim($matches[1]);  // puede contener "FEE"
                        $parteDespuesDelGuion = trim($matches[2]); // cliente

                        return Str::contains(Str::upper($parteAntesDelGuion), 'FEE') &&
                            $parteDespuesDelGuion === $cliente;
                    }
                    return false;
                })
                ->sum('importe_tablero');

            $reales[$cliente] = $total ?: 0;
        }

        $realesTotal = $reales->sum();

        return view('creartablerodos', compact('tablero', 'clientes', 'ultimoMes', 'reales', 'realesTotal', 'reporte'));
    }
    
    public function guardarDatos(Request $request, $id)
    {
        $request->validate([
            //'acumulado_mes_anterior' => 'required|array',
            //'acumulado_mes_anterior.*.total' => 'nullable|numeric',

            'mes_dinamico' => 'required|array',
            'mes_dinamico.*.plan' => 'required|numeric',

            //'acumulado_no_alcanzado' => 'required|array',
            //'acumulado_no_alcanzado.*.resultado' => 'nullable|numeric',

            'acciones_a_tomar' => 'required|array',
            'acciones_a_tomar.*.servicio' => 'required|string|max:255',
            'acciones_a_tomar.*.propuesta' => 'required|string|max:255',
            'acciones_a_tomar.*.monto' => 'required|numeric',
            'acciones_a_tomar.*.fecha' => 'required|date',
        ]);

        // Acumulado Mes Anterior
        //foreach ($request->input('acumulado_mes_anterior', []) as $cliente => $data) {
        //    AcumuladoMesAnterior::updateOrCreate(
        //        ['tablero_id' => $id, 'cliente' => $cliente],
        //        ['total' => $data['total']]
        //    );
        //}

        // Mes Dinámico
        foreach ($request->input('mes_dinamico', []) as $cliente => $data) {
            $real = $data['real'] ?? 0;
            $plan = $data['plan'] ?? null;

            MesDinamico::updateOrCreate(
                ['tablero_id' => $id, 'cliente' => $cliente],
                [
                    'real' => $data['real'] ?? 0,  
                    'plan' => $data['plan']
                ]
            );
        }

        // Acumulado No Alcanzado
       // foreach ($request->input('acumulado_no_alcanzado', []) as $cliente => $data) {
         //   AcumuladoNoAlcanzado::updateOrCreate(
           //     ['tablero_id' => $id, 'cliente' => $cliente],
           //     ['resultado_acumulado' => $data['resultado']]
          //  );
       // }

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
        $reporte = $tablero->evento;

        $clientes = \App\Models\Consolidado::where('tablero_id', $id)
            ->select('feat_business_tablero')
            ->distinct()
            ->get()
            ->map(function ($item) {
                if (preg_match('/\-\s*(.+)/', $item->feat_business_tablero, $matches)) {
                    return trim($matches[1]);
                }
                return null;
            })
            ->filter()
            ->unique()
            ->values();

        // Calcula reales para cada cliente (como en datos())
        $reales = collect();
        foreach ($clientes as $cliente) {
            $total = \App\Models\Consolidado::where('tablero_id', $id)
                ->get()
                ->filter(function ($item) use ($cliente) {
                    if (preg_match('/(.+?)\s*-\s*(.+)/', $item->feat_business_tablero, $matches)) {
                        $antesDelGuion = Str::upper(trim($matches[1]));
                        $despuesDelGuion = trim($matches[2]);

                        return Str::contains($antesDelGuion, 'FEE') && $despuesDelGuion === $cliente;
                    }
                    return false;
                })
                ->sum('importe_tablero');
            $reales[$cliente] = $total ?: 0;
        }

        $acumuladoMesAnterior = AcumuladoMesAnterior::where('tablero_id', $id)->get()->keyBy('cliente');
        $mesDinamico = MesDinamico::where('tablero_id', $id)->get()->keyBy('cliente');
        $acumuladoNoAlcanzado = AcumuladoNoAlcanzado::where('tablero_id', $id)->get()->keyBy('cliente');
        $accionesATomar = AccionATomar::where('tablero_id', $id)->get()->keyBy('cliente');

        $ultimaFecha = Consolidado::where('tablero_id', $id)
            ->orderByDesc('fecha')
            ->value('fecha');

        $ultimoMes = $ultimaFecha ? Carbon::parse($ultimaFecha)->translatedFormat('F Y') : 'Mes desconocido';

        // array para mesDinamico con cálculos VS Plan y porcentaje
        $mesDinamicoCalculado = [];

        foreach ($clientes as $cliente) {
            $plan = $mesDinamico[$cliente]->plan ?? null;
            $real = $reales[$cliente] ?? 0;
            $vsPlan = null;
            $porcentaje = null;

            if ($plan !== null) {
                $vsPlan = $real - $plan;
            
                if ($plan == 0) {
                    // Si el plan es = 0 entonces no se puede dividir 
                    if ($real > 0) {
                        $porcentaje = 100;
                    } elseif ($real < 0) {
                        $porcentaje = -100;
                    } else {
                        $porcentaje = 0;
                    }
                } else {
                    // Plan distinto de 0, se puede dividir sin problemas :D 
                    $porcentaje = round((($real / $plan) - 1) * 100);
                }
            } else {
                $vsPlan = 0;
                $porcentaje = 0;
            }

            $mesDinamicoCalculado[$cliente] = (object)[
                'plan' => $plan,
                'real' => $real,
                'vs_plan' => $vsPlan,
                'porcentaje' => $porcentaje,
            ];
        }

        //Agarro los tableros anteriores al actual ordenados por fecha
        $tablerosAnteriores = Tablero::where('evento_id', $tablero->evento_id)
            ->where('id', '!=', $id)
            ->where('created_at', '<', $tablero->created_at)
            ->orderBy('created_at')
            ->get();

        
        //calculo acumulado del mes anterior (real - plan por cliente)
        $acumuladoAnterior = [];
        foreach ($clientes as $cliente) {
            $totalVsPlanAnterior = 0;

            foreach ($tablerosAnteriores as $tableroAnterior) {
                $dato = MesDinamico::where('tablero_id', $tableroAnterior->id)
                    ->where('cliente', $cliente)
                    ->first();

                if ($dato) {
                    $real = $dato->real ?? 0;
                    $plan = $dato->plan ?? 0;
                    $totalVsPlanAnterior += ($real - $plan);
                }
            }

            $acumuladoAnterior[$cliente] = $totalVsPlanAnterior;
        }

        $acumuladoTotal = [];

        foreach ($clientes as $cliente) {
            $anterior = $acumuladoAnterior[$cliente] ?? 0;
            $vsPlanActual = $mesDinamicoCalculado[$cliente]->vs_plan ?? 0;

            $acumuladoTotal[$cliente] = $anterior + $vsPlanActual;
        }

        return view('tablerodetalle', compact(
            'tablero',
            'clientes',
            'acumuladoMesAnterior',
            'mesDinamicoCalculado',
            'acumuladoNoAlcanzado',
            'accionesATomar',
            'ultimoMes',
            'reporte',
            'acumuladoAnterior',
            'acumuladoTotal'
        ));
    }


    public function eliminar($id)
    {
        $tablero = \App\Models\Tablero::findOrFail($id); 

        \App\Models\Consolidado::where('tablero_id', $id)->delete();
        \App\Models\AcumuladoMesAnterior::where('tablero_id', $id)->delete();
        \App\Models\MesDinamico::where('tablero_id', $id)->delete();
        \App\Models\AcumuladoNoAlcanzado::where('tablero_id', $id)->delete();
        \App\Models\AccionATomar::where('tablero_id', $id)->delete();

        $tablero->delete(); // Elimina el tablero de la base de datos

         return redirect()->route('tablero')->with('success', 'Tablero eliminado correctamente.');
    }




}