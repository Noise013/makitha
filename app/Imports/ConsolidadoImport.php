<?php

namespace App\Imports;

use App\Models\Consolidado;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Str;

HeadingRowFormatter::default('none');

class ConsolidadoImport implements OnEachRow, WithHeadingRow
{
    protected $tableroId;
    protected $eventoId;
    protected $nombreTablero;

    public function __construct($tableroId, $eventoId, $nombreTablero) 
    {
        $this->tableroId = $tableroId;
        $this->eventoId = $eventoId;
        $this->nombreTablero = $nombreTablero; 
    }


    public function onRow(Row $row)
    {
        $dataOriginal = $row->toArray();

        // DEBUG para ver las columnas originales:
         //dd($dataOriginal);

        // Normaliza claves a minúsculas y sin espacios ni tildes
        $data = [];
        foreach ($dataOriginal as $key => $value) {
            $keyNormalizado = Str::of($key)
                ->lower()
                ->replaceMatches('/\s+/', '_')   // espacios por guion bajo
                ->replace('á', 'a')
                ->replace('é', 'e')
                ->replace('í', 'i')
                ->replace('ó', 'o')
                ->replace('ú', 'u')
                ->replace('ü', 'u')
                ->replace('ñ', 'n')
                ->toString();
            $data[$keyNormalizado] = $value;
        }

        // DEBUG para ver claves normalizadas y valores
        // dd($data);

        // Mapa de encabezados alternativos
        $mapa = [
            'descripcion_tablero'     => ['descripcion'],
            'feat_business_tablero'   => ['feat_business'],
            'cargar_a'                => ['cargar_a', 'a_donde_se_carga', 'se_cargo_a', 'cargar_a_', 'se_cargo_a_', 'a_donde_se_cargo'],
            'importe_tablero'         => ['importe'],
        ];

        // Asignar valores mapeando con los sinónimos
        $valores = [];
        foreach ($mapa as $campoFinal => $posiblesNombres) {
            foreach ($posiblesNombres as $alias) {
                if (isset($data[$alias])) {
                    $valores[$campoFinal] = $data[$alias];
                    break;
                }
            }

            // Si no se encontró ninguna coincidencia, asignar null
            if (!isset($valores[$campoFinal])) {
                $valores[$campoFinal] = null;
            }
        }

        $fechaOriginal = $data['fecha'] ?? null;
        $fecha = null;

        if ($fechaOriginal !== null) {
            if (is_numeric($fechaOriginal)) {
                $fecha = Date::excelToDateTimeObject($fechaOriginal)->format('Y-m-d');
            } else {
                try {
                    $fecha = Carbon::parse($fechaOriginal)->format('Y-m-d');
                } catch (\Exception $e) {
                    $fecha = null; // si no se puede parsear
                }
            }
        }

         Consolidado::create([
            'fecha'                  => $fecha,
            'descripcion_tablero'   => $valores['descripcion_tablero'],
            'feat_business_tablero' => $valores['feat_business_tablero'],
            'importe_tablero'       => $valores['importe_tablero'],
            'nombre_tablero'        => $this->nombreTablero,
            'tablero_id'            => $this->tableroId,
            'evento_id'             => $this->eventoId,
        ]);
    }
}