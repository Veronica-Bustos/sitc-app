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

class StockByLocationExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected Collection $items;

    protected ?string $locationName;

    public function __construct(Collection $items, ?string $locationName = null)
    {
        $this->items = $items;
        $this->locationName = $locationName;
    }

    public function collection(): Collection
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Nombre',
            'Categoría',
            'Ubicación',
            'Estado',
            'Condición',
            'Marca',
            'Modelo',
            'N° Serie',
            'Valor Compra',
            'Valor Actual',
        ];
    }

    public function map($item): array
    {
        return [
            $item->code,
            $item->name,
            $item->category?->name ?? 'N/A',
            $item->currentLocation?->name ?? 'Sin ubicación',
            $this->getStatusLabel($item->status),
            $this->getConditionLabel($item->condition),
            $item->brand ?? 'N/A',
            $item->model ?? 'N/A',
            $item->serial_number ?? 'N/A',
            $item->purchase_price ?? 0,
            $item->current_value ?? 0,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => '#,##0.00',
            'K' => '#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Título del reporte
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'Reporte de Stock por Ubicación');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Subtítulo con ubicación si aplica
        if ($this->locationName) {
            $sheet->mergeCells('A2:K2');
            $sheet->setCellValue('A2', 'Ubicación: '.$this->locationName);
            $sheet->getStyle('A2')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        }

        // Encabezados (fila 3 o 4 dependiendo del subtítulo)
        $headerRow = $this->locationName ? 4 : 3;
        $sheet->getStyle("A{$headerRow}:K{$headerRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Bordes para toda la tabla
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A{$headerRow}:K{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Totales al final
        $totalRow = $lastRow + 1;
        $sheet->setCellValue("A{$totalRow}", 'TOTALES');
        $sheet->mergeCells("A{$totalRow}:I{$totalRow}");
        $sheet->getStyle("A{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);

        $sheet->setCellValue("J{$totalRow}", '=SUM(J'.($headerRow + 1).':J'.$lastRow.')');
        $sheet->setCellValue("K{$totalRow}", '=SUM(K'.($headerRow + 1).':K'.$lastRow.')');
        $sheet->getStyle("J{$totalRow}:K{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'numberFormat' => ['formatCode' => '#,##0.00'],
        ]);

        return [];
    }

    private function getStatusLabel(?string $status): string
    {
        return match ($status) {
            'available' => 'Disponible',
            'in_use' => 'En Uso',
            'in_repair' => 'En Reparación',
            'damaged' => 'Dañado',
            'lost' => 'Perdido',
            'retired' => 'Retirado',
            default => $status ?? 'Desconocido',
        };
    }

    private function getConditionLabel(?string $condition): string
    {
        return match ($condition) {
            'excellent' => 'Excelente',
            'good' => 'Bueno',
            'fair' => 'Regular',
            'poor' => 'Malo',
            default => $condition ?? 'Desconocido',
        };
    }
}
