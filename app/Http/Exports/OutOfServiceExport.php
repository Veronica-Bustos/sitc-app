<?php

namespace App\Http\Exports;

use App\Models\Item;
use Carbon\Carbon;
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

class OutOfServiceExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected Collection $items;

    protected array $filters;

    public function __construct(Collection $items, array $filters = [])
    {
        $this->items = $items;
        $this->filters = $filters;
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
            'Estado',
            'Categoría',
            'Última Ubicación',
            'Marca',
            'Modelo',
            'Días Fuera Servicio',
            'Valor Compra',
            'Valor Actual',
            'Motivo/Notas',
        ];
    }

    public function map($item): array
    {
        $daysOutOfService = $this->calculateDaysOutOfService($item);

        return [
            $item->code,
            $item->name,
            $this->getStatusLabel($item->status),
            $item->category?->name ?? 'N/A',
            $item->currentLocation?->name ?? 'Sin ubicación',
            $item->brand ?? 'N/A',
            $item->model ?? 'N/A',
            $daysOutOfService,
            $item->purchase_price ?? 0,
            $item->current_value ?? 0,
            $this->getLastMaintenanceNotes($item),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => '#,##0.00',
            'J' => '#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Título del reporte
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'Reporte de Ítems Fuera de Servicio');
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
            $filterParts = [];

            if (! empty($this->filters['status'])) {
                $statuses = is_array($this->filters['status']) ? $this->filters['status'] : [$this->filters['status']];
                $statusLabels = array_map(fn ($s) => $this->getStatusLabel($s), $statuses);
                $filterParts[] = 'Estados: '.implode(', ', $statusLabels);
            }
            if (! empty($this->filters['date_from'])) {
                $filterParts[] = 'Desde: '.$this->filters['date_from'];
            }

            if (! empty($filterParts)) {
                $sheet->mergeCells('A2:K2');
                $sheet->setCellValue('A2', 'Filtros aplicados: '.implode(' | ', $filterParts));
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
        $sheet->getStyle("A{$headerRow}:K{$headerRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626'],
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

        // Colores por estado
        for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
            $statusCell = $sheet->getCell("C{$row}");
            $status = $statusCell->getValue();
            $color = $this->getStatusColor($status);

            if ($color) {
                $sheet->getStyle("C{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $color],
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'],
                        'bold' => true,
                    ],
                ]);
            }
        }

        // Totales al final
        $totalRow = $lastRow + 1;
        $sheet->setCellValue("A{$totalRow}", 'TOTALES');
        $sheet->mergeCells("A{$totalRow}:H{$totalRow}");
        $sheet->getStyle("A{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);

        $sheet->setCellValue("I{$totalRow}", '=SUM(I'.($headerRow + 1).':I'.$lastRow.')');
        $sheet->setCellValue("J{$totalRow}", '=SUM(J'.($headerRow + 1).':J'.$lastRow.')');
        $sheet->getStyle("I{$totalRow}:J{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'numberFormat' => ['formatCode' => '#,##0.00'],
        ]);

        // Resumen por estado
        $summaryRow = $lastRow + 3;
        $sheet->setCellValue("A{$summaryRow}", 'RESUMEN POR ESTADO');
        $sheet->mergeCells("A{$summaryRow}:K{$summaryRow}");
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
        $sheet->setCellValue("A{$summaryRow}", 'En Reparación:');
        $sheet->setCellValue("B{$summaryRow}", $this->items->where('status', 'in_repair')->count());

        $summaryRow++;
        $sheet->setCellValue("A{$summaryRow}", 'Dañados:');
        $sheet->setCellValue("B{$summaryRow}", $this->items->where('status', 'damaged')->count());

        $summaryRow++;
        $sheet->setCellValue("A{$summaryRow}", 'Perdidos:');
        $sheet->setCellValue("B{$summaryRow}", $this->items->where('status', 'lost')->count());

        $summaryRow++;
        $sheet->setCellValue("A{$summaryRow}", 'Retirados:');
        $sheet->setCellValue("B{$summaryRow}", $this->items->where('status', 'retired')->count());

        return [];
    }

    private function getStatusLabel(?string $status): string
    {
        return match ($status) {
            'in_repair' => 'En Reparación',
            'damaged' => 'Dañado',
            'lost' => 'Perdido',
            'retired' => 'Retirado',
            default => $status ?? 'Desconocido',
        };
    }

    private function getStatusColor(?string $status): ?string
    {
        return match ($status) {
            'En Reparación' => 'F59E0B',
            'Dañado' => 'EF4444',
            'Perdido' => '6B7280',
            'Retirado' => '1F2937',
            default => null,
        };
    }

    private function calculateDaysOutOfService(Item $item): int
    {
        // Intentar obtener la fecha del último mantenimiento o movimiento
        $lastMaintenance = $item->maintenanceRecords()
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest('request_date')
            ->first();

        if ($lastMaintenance) {
            return Carbon::parse($lastMaintenance->request_date)->diffInDays(now());
        }

        // Si no hay mantenimiento, usar updated_at del ítem
        return $item->updated_at->diffInDays(now());
    }

    private function getLastMaintenanceNotes(Item $item): string
    {
        $lastMaintenance = $item->maintenanceRecords()
            ->latest('request_date')
            ->first();

        if ($lastMaintenance) {
            return $lastMaintenance->description ?? 'Sin notas';
        }

        return 'Sin registro de mantenimiento';
    }
}
