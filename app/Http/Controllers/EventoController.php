<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Evento;
use App\Models\Proyeccion;
use App\Models\Movimiento;
use App\Models\Consolidado;
use App\Models\Tablero;
use App\Models\AcumuladoMesAnterior;
use App\Models\MesDinamico;
use App\Models\AcumuladoNoAlcanzado;
use App\Models\AccionATomar;

class EventoController extends Controller
{
    public function crear()
    {
        $eventos = Evento::orderBy('created_at', 'desc')->get();
        return view('crear', compact('eventos'));
    }

    public function guardar()
    {
        do {
            $eventoId = (string) Str::random(16);
        } while (Evento::where('id', $eventoId)->exists());

        Evento::create(['id' => $eventoId]);

        return redirect()->route('importar.form', ['evento' => $eventoId]);
    }

    public function ver(Request $request)
    {
        $eventoId = $request->query('id');
    
        // Buscar evento
        $evento = Evento::find($eventoId);
        if (!$evento) {
            abort(404, 'El evento no existe');
        }
    
        // Obtener proyecciones mensuales y total
        $pMensuales = Proyeccion::where('evento_id', $eventoId)->get();
        $totalProyeccion = $pMensuales->sum('proyeccion');
    
        // Obtener movimientos del evento
        $movimientos = Movimiento::where('evento', $eventoId)->get();
    
        // Inicializar arreglo para acumulados por mes (mes con formato 1..12)
        $ejecutadoPorMes = array_fill(1, 12, 0);
    
        foreach ($movimientos as $mov) {
            $valor = $mov->feat_business;
        
            if ($valor && is_string($valor)) {
                // Extraer texto después de los corchetes
                preg_match('/\](.*?)\-/', $valor, $matches);
                $descripcion = isset($matches[1]) ? trim($matches[1]) : '';
        
                // Revisar si contiene "FEE" (insensible a mayúsculas) y el importe es positivo
                if (stripos($descripcion, 'FEE') !== false && $mov->importe > 0) {

                
                    $mes = date('n', strtotime($mov->fecha)); // 1..12
                    $ejecutadoPorMes[$mes] += $mov->importe;
                }
            }
        }
        
        // Ordenar por mes numérico
        ksort($ejecutadoPorMes);
    
        // Pasar arreglo con meses en formato '01'...'12'
        $ejecutadoPorMesCompletos = [];
        foreach ($ejecutadoPorMes as $mesInt => $valor) {
            $key = str_pad((string) $mesInt, 2, '0', STR_PAD_LEFT);
            $ejecutadoPorMesCompletos[$key] = $valor;
        }
    
        // Mapeo de nombres de meses a números
        $mesesMap = [
            // Cortos en mayúscula
            'ENE' => '01', 'FEB' => '02', 'MAR' => '03', 'ABR' => '04',
            'MAY' => '05', 'JUN' => '06', 'JUL' => '07', 'AGO' => '08',
            'SEP' => '09', 'OCT' => '10', 'NOV' => '11', 'DIC' => '12',
    
            // Nombres completos en minúscula
            'enero' => '01', 'febrero' => '02', 'marzo' => '03', 'abril' => '04',
            'mayo' => '05', 'junio' => '06', 'julio' => '07', 'agosto' => '08',
            'septiembre' => '09', 'octubre' => '10', 'noviembre' => '11', 'diciembre' => '12',
        ];
    
        // Convertir proyecciones mensuales a un array por mes: '01' => valor
        $metaPorMes = [];
        foreach ($pMensuales as $p) {
            $mesRaw = trim($p->mes);
            $mesKey = strtolower($mesRaw);
    
            if (isset($mesesMap[$mesKey])) {
                $mesNum = $mesesMap[$mesKey];
                $metaPorMes[$mesNum] = $p->proyeccion;
            } elseif (is_numeric($mesKey)) {
                $mesNum = str_pad($mesKey, 2, '0', STR_PAD_LEFT);
                $metaPorMes[$mesNum] = $p->proyeccion;
            }
        }
    
        // Calcular diferencia ejecutado - meta asegurando todos los meses del año
        $vsMeta = [];
        foreach (range(1, 12) as $i) {
            $mes = str_pad($i, 2, '0', STR_PAD_LEFT);
            $ejecutado = $ejecutadoPorMesCompletos[$mes] ?? 0;
            $meta = $metaPorMes[$mes] ?? 0;
            $vsMeta[$mes] = $ejecutado - $meta;
        }
        ksort($vsMeta);
    
        return view('eventodetalle', compact(
            'evento',
            'movimientos',
            'pMensuales',
            'totalProyeccion',
            'ejecutadoPorMesCompletos',
            'metaPorMes',
            'vsMeta'
        ));
    }

    public function eliminar($id)
    {
        $evento = Evento::findOrFail($id);

        // eliminar movimientos del evento
        Movimiento::where('evento', $id)->delete();

        // eliminar proyecciones del evento
        Proyeccion::where('evento_id', $id)->delete();

        // eliminar consolidados del evento
        Consolidado::where('evento_id', $id)->delete();

        // eliminar tableros asociados y datos
        $tableros = Tablero::where('evento_id', $id)->get();
        foreach ($tableros as $tablero) {
            AcumuladoMesAnterior::where('tablero_id', $tablero->id)->delete();
            MesDinamico::where('tablero_id', $tablero->id)->delete();
            AcumuladoNoAlcanzado::where('tablero_id', $tablero->id)->delete();
            AccionATomar::where('tablero_id', $tablero->id)->delete();
            $tablero->delete();
        }

        // eliminar el evento
        $evento->delete();

        return redirect()->route('eventos.crear')->with('success', 'Reporte y los datos relacionados fueron eliminados correctamente.');
    }

}