<?php

namespace App\Imports;

use App\Models\Movimiento;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

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
        
        return new Movimiento([
            'fecha'         => \Carbon\Carbon::parse($row['Fecha'])->format('Y-m-d'),
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
