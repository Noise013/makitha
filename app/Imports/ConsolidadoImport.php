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

    public function __construct($tableroId, $eventoId)
    {
        $this->tableroId = $tableroId;
        $this->eventoId = $eventoId;
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
            'fecha'          => $fecha,
            'descripcion'    => $data['descripcion'] ?? null,
            'feat_business'  => $data['feat_business'] ?? null,
            'cargar_a'       => $data['cargar_a'] ?? null,
            'importe'        => $data['importe'] ?? null,
            'tablero_id'     => $this->tableroId,
            'evento_id'      => $this->eventoId,
        ]);
    }
}