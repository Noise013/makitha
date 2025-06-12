<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalImporte = Dashboard::sum('importe');
        $totalFilas = Dashboard::count();

        // Columnas que contienen nombres de clientes
        $columnasClientes = ['feat_business', 'big_brothers', 'g_and_a', 'corporativo'];

        // Obtener clientes únicos
        $clientes = collect();
        foreach ($columnasClientes as $columna) {
            $clientes = $clientes->merge(
                Dashboard::whereNotNull($columna)
                    ->pluck($columna)
                    ->map(function ($valor) {
                        return trim(explode('-', $valor)[1] ?? '');
                    })
            );
        }
        $clientesUnicos = $clientes->unique()->values();

        // Sumar importes por cliente
        $clientesImportes = [];
        foreach ($columnasClientes as $columna) {
            $datos = Dashboard::select(
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

        // Obtener ingresos totales por mes (sin agrupar por cliente)
        $ingresosPorMes = Dashboard::select(
                DB::raw("DATE_FORMAT(fecha, '%Y-%m') as mes"),
                DB::raw("SUM(importe) as total")
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Traducir meses a español
        $mesesTraducidos = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];

        $meses = [];
        $importes = [];

        foreach ($ingresosPorMes as $registro) {
            $partes = explode('-', $registro->mes); // [año, mes]
            $nombreMes = $mesesTraducidos[$partes[1]] ?? $registro->mes;
            $meses[] = $nombreMes;
            $importes[] = round($registro->total, 2);
        }

        return view('dashboard', compact(
            'totalImporte',
            'totalFilas',
            'clientesUnicos',
            'clientesImportes',
            'meses',
            'importes'
        ));
    }
}