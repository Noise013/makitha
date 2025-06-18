<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', 'all');

        $aniosDisponibles = Dashboard::selectRaw('YEAR(fecha) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $baseQuery = Dashboard::query();

        if ($year !== 'all') {
            $baseQuery->whereYear('fecha', $year);
        }

        $totalImporte = (clone $baseQuery)->sum('importe');
        $totalFilas = (clone $baseQuery)->count();

        $columnasClientes = ['feat_business', 'big_brothers', 'g_and_a', 'corporativo'];

        $clientes = collect();
        foreach ($columnasClientes as $columna) {
            $clientes = $clientes->merge(
                (clone $baseQuery)->whereNotNull($columna)
                    ->pluck($columna)
                    ->map(function ($valor) {
                        return trim(explode('-', $valor)[1] ?? '');
                    })
            );
        }
        $clientesUnicos = $clientes->unique()->values();

        $clientesImportes = [];
        foreach ($columnasClientes as $columna) {
            $datos = (clone $baseQuery)
                ->select(
                    DB::raw("TRIM(SUBSTRING_INDEX($columna, '-', -1)) as cliente"),
                    DB::raw("SUM(importe) as total")
                )
                ->whereNotNull($columna)
                ->groupBy('cliente')
                ->get();

            foreach ($datos as $dato) {
                $cliente = trim($dato->cliente);
                $clientesImportes[$cliente] = ($clientesImportes[$cliente] ?? 0) + $dato->total;
            }
        }

        // Traducción de meses
        $mesesTraducidos = [
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
            '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
            '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
            '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre',
        ];

        $meses = [];
$importes = [];

if ($year === 'all') {
    $resultados = Dashboard::select(
            DB::raw("YEAR(fecha) as anio"),
            DB::raw("MONTH(fecha) as mes"),
            DB::raw("SUM(importe) as total")
        )
        ->groupBy('anio', 'mes')
        ->orderBy('anio')
        ->orderBy('mes')
        ->get();

    $datosPorAnio = [];
    $mesesNumericos = [];

    foreach ($resultados as $fila) {
        $anio = $fila->anio;
        $mes = str_pad($fila->mes, 2, '0', STR_PAD_LEFT);
        $nombreMes = $mesesTraducidos[$mes];

        $datosPorAnio[$anio][$nombreMes] = round($fila->total, 2);
        $mesesNumericos[$mes] = $nombreMes; // evita duplicados
    }

    // Solo usamos los meses que tienen datos (y comunes si es necesario)
    ksort($mesesNumericos);
    $meses = array_values($mesesNumericos);

    foreach ($datosPorAnio as $anio => $datosMes) {
        $serie = [];
        foreach ($meses as $mes) {
            $serie[] = $datosMes[$mes] ?? 0;
        }
        $importes[] = [
            'name' => $anio,
            'data' => $serie
        ];
    }

} else {
    $resultados = Dashboard::select(
            DB::raw("MONTH(fecha) as mes"),
            DB::raw("SUM(importe) as total")
        )
        ->whereYear('fecha', $year)
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

    $serie = [];
    foreach ($resultados as $fila) {
        $mes = str_pad($fila->mes, 2, '0', STR_PAD_LEFT);
        $nombreMes = $mesesTraducidos[$mes];

        $meses[] = $nombreMes;
        $serie[] = round($fila->total, 2);
    }

    $importes[] = [
        'name' => $year,
        'data' => $serie
    ];
}

        return view('dashboard', compact(
            'totalImporte',
            'totalFilas',
            'clientesUnicos',
            'clientesImportes',
            'meses',
            'importes',
            'aniosDisponibles',
            'year',
        ));
    }
}