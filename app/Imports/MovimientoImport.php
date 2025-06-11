<?php

namespace App\Imports;

use App\Models\Movimiento;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date;

HeadingRowFormatter::default('none');


class MovimientoImport implements ToModel, WithHeadingRow
{
    protected $eventoId;

    public function __construct($eventoId)
    {
        $this->eventoId = $eventoId;
    }
    
    public function model(array $row)
    {
        $fechaOriginal = $row['Fecha'];
        $fecha = null;

        //detecta si es un numero serial de excel
         if (is_numeric($fechaOriginal)) {
        //número serial de Excel
        $fecha = Date::excelToDateTimeObject($fechaOriginal)->format('Y-m-d');
        } else {
        //string tipo "2025-06-02"
        $fecha = \Carbon\Carbon::parse($fechaOriginal)->format('Y-m-d');
        }
        
        return new Movimiento([
            'fecha'         => $fecha,
            'descripcion'   => $row['Descripción'] ?? null,
            'feat_business' => $row['FEAT BUSINESS'] ?? null,
            'big_brothers'  => $row['BIG BROTHERS'] ?? null,
            'g_and_a'       => $row['G&A'] ?? null,
            'corporativo'   => $row['CORPORATIVO'] ?? null,
            'importe'       => $row['Importe'],
            'evento'        => $this->eventoId,
        ]);
    }
}