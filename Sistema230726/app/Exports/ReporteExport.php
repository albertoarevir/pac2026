<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class ReporteExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;
    public function __construct($query)
    {
        // Recibe la query con los filtros aplicados
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID PROYECTO', 'AÑO', 'DEPARTAMENTO', 'ÍTEM PRESUPUESTARIO', 'CANTIDAD', 
            'PRESUPUESTO INICIAL', 'N° LICITACIÓN', 'MODALIDAD', 'ESTADO LICITACIÓN',
            'N° ORDEN COMPRA', 'MONTO COMPRA', 'ESTADO COMPRA', 'FECHA ACTUALIZACIÓN'
        ];
    }

    public function map($fila): array
    {
        return [
            $fila->id_proyecto,
            $fila->anio,
            $fila->departamento,
            $fila->item_presupuestario,
            $fila->cantidad,
            $fila->presupuesto_inicial_sap,
            $fila->numero_licitacion ?? '—',
            $fila->modalidad_compra ?? '—',
            $fila->estado_licitacion ?? '—',
            $fila->numero_orden ?? '—',
            $fila->monto_compra ?? 0,
            $fila->estado_compra ?? '—',
            $fila->fecha_actualizacion_compra ?? '—',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la fila de encabezados (Fila 1)
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1A2744']
                ],
            ],
        ];
    }
}