<?php

namespace App\Http\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MovementHistoryExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected Collection $movements;

    protected array $filters;

    public function __construct(Collection $movements, array $filters = [])
    {
        $this->movements = $movements;
        $this->filters = $filters;
    }

    public function collection(): Collection
    {
        return $this->movements;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Código Ítem',
            'Nombre Ítem',
            'Tipo Movimiento',
            'Origen',
            'Destino',
            'Usuario',
            'Cantidad',
            'Documento Ref.',
            'Notas',
        ];
    }

    public function map($movement): array
    {
        return [
            $movement->performed_at?->format('d/m/Y H:i') ?? 'N/A',
            $movement->item?->code ?? 'N/A',
            $movement->item?->name ?? 'N/A',
            $this->getMovementTypeLabel($movement->movement_type),
            $movement->fromLocation?->name ?? 'Inventario',
            $movement->toLocation?->name ?? 'Externo',
            $movement->user?->name ?? 'N/A',
            $movement->quantity ?? 1,
            $movement->reference_document ?? 'N/A',
            $movement->notes ?? 'N/A',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'DD/MM/YYYY HH:MM',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Título del reporte
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'Reporte de Historial de Movimientos');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Información de filtros aplicados
        $row = 2;
        if (! empty($this->filters)) {
            $filterText = 'Filtros aplicados: ';
            $filterParts = [];

            if (! empty($this->filters['date_from'])) {
                $filterParts[] = 'Desde: '.$this->filters['date_from'];
            }
            if (! empty($this->filters['date_to'])) {
                $filterParts[] = 'Hasta: '.$this->filters['date_to'];
            }
            if (! empty($this->filters['movement_type'])) {
                $filterParts[] = 'Tipo: '.$this->getMovementTypeLabel($this->filters['movement_type']);
            }
            if (! empty($this->filters['item_id'])) {
                $filterParts[] = 'Ítem específico';
            }

            if (! empty($filterParts)) {
                $sheet->mergeCells('A2:J2');
                $sheet->setCellValue('A2', $filterText.implode(' | ', $filterParts));
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $row = 3;
            }
        }

        // Encabezados
        $headerRow = $row + 1;
        $sheet->getStyle("A{$headerRow}:J{$headerRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Bordes para toda la tabla
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A{$headerRow}:J{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Resumen al final
        $summaryRow = $lastRow + 2;
        $sheet->setCellValue("A{$summaryRow}", 'RESUMEN');
        $sheet->mergeCells("A{$summaryRow}:J{$summaryRow}");
        $sheet->getStyle("A{$summaryRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB'],
            ],
        ]);

        $summaryRow++;
        $sheet->setCellValue("A{$summaryRow}", 'Total de movimientos:');
        $sheet->setCellValue("B{$summaryRow}", $this->movements->count());

        return [];
    }

    private function getMovementTypeLabel(?string $type): string
    {
        return match ($type) {
            'check_in' => 'Entrada',
            'check_out' => 'Salida',
            'transfer' => 'Transferencia',
            'return' => 'Devolución',
            'audit_adjustment' => 'Ajuste Auditoría',
            'disposal' => 'Baja',
            default => $type ?? 'Desconocido',
        };
    }
}
