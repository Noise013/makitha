<?php

namespace App\Imports;

use App\Models\Consolidado;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;


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
        $data = $row->toArray();

        $fechaOriginal = $data['Fecha'] ?? null;
        $fecha = null;

        if ($fechaOriginal !== null) {
            if (is_numeric($fechaOriginal)) {
                $fecha = Date::excelToDateTimeObject($fechaOriginal)->format('Y-m-d');
            } else {
                $fecha = Carbon::parse($fechaOriginal)->format('Y-m-d');
            }
        }

        Consolidado::create([
            'fecha'          => $fecha,
            'descripcion'    => $data['Descripción'] ?? null,
            'feat_business'  => $data['FEAT BUSINESS'] ?? null,
            'cargar_a'       => $data['CARGAR A'] ?? null,
            'importe'        => $data['Importe'] ?? null,
            'tablero_id'     => $this->tableroId,
            'evento_id'      => $this->eventoId,
        ]);
    }
}
