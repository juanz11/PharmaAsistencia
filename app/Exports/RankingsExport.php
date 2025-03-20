<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class RankingsExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithCustomStartCell, WithMapping
{
    protected $rankings;
    protected $title;
    protected $type;

    public function __construct($rankings, $title, $type)
    {
        $this->rankings = $rankings;
        $this->title = $title;
        $this->type = $type;
    }

    public function collection()
    {
        return new Collection($this->rankings);
    }

    public function headings(): array
    {
        return [
            '#',
            'Nombre',
            'Mejor Tiempo',
            'Dispositivo',
            'Horas Trabajadas',
            'Días a Tiempo',
            'Total de Días',
        ];
    }

    public function map($ranking): array
    {
        return [
            $ranking['position'],
            $ranking['name'],
            $ranking['best_time'],
            $ranking['device'],
            $ranking['working_hours'] ?? '--:--',
            $ranking['on_time_days'],
            $ranking['total_days'],
        ];
    }

    public function title(): string
    {
        return 'Rankings';
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // Posición
            'B' => 30,  // Nombre
            'C' => 15,  // Mejor Tiempo
            'D' => 25,  // Dispositivo
            'E' => 18,  // Horas Trabajadas
            'F' => 15,  // Días a Tiempo
            'G' => 15,  // Total de Días
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Información del reporte
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', $this->title);
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Horario esperado: ' . ($this->type === 'entrada' ? '8:40 AM' : '4:55 PM'));
        
        // Estilo del título
        $titleStyle = $sheet->getStyle('A1');
        $titleStyle->getFont()
            ->setBold(true)
            ->setSize(14)
            ->setColor(new Color('2C3E50'));
        
        $titleStyle->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        
        $titleStyle->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color('F8F9FA'));

        // Estilo de la información adicional
        $infoStyle = $sheet->getStyle('A2');
        $infoStyle->getFont()
            ->setSize(11)
            ->setColor(new Color('7F8C8D'));
        
        $infoStyle->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // Estilo de los encabezados
        $headerStyle = $sheet->getStyle('A3:G3');
        $headerStyle->getFont()
            ->setBold(true)
            ->setColor(new Color('FFFFFF'))
            ->setSize(11);
        
        $headerStyle->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color('4B6584'));
        
        $headerStyle->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension(3)->setRowHeight(20);

        // Estilo para las filas de datos
        $lastRow = count($this->rankings) + 3;
        $dataRange = 'A4:G' . $lastRow;
        
        // Estilo general para todas las celdas de datos
        $dataStyle = $sheet->getStyle($dataRange);
        $dataStyle->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        
        $dataStyle->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('DEE2E6'));

        // Alineación específica para nombres (columna B)
        $sheet->getStyle('B4:B' . $lastRow)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Aplicar colores alternados a las filas
        for ($row = 4; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':G' . $row)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('F8F9FA'));
            }
        }

        // Resaltar dispositivos inusuales y porcentajes
        $row = 4;
        foreach ($this->rankings as $ranking) {
            // Resaltar dispositivo inusual
            if ($ranking['is_unusual_device']) {
                $sheet->getStyle('D' . $row)
                    ->getFont()
                    ->setColor(new Color('E74C3C'))
                    ->setBold(true);
            }

            // Colorear porcentajes
            // $percentage = floatval($ranking['percentage']);
            // if ($percentage >= 80) {
            //     $sheet->getStyle('G' . $row)
            //         ->getFont()
            //         ->setColor(new Color('27AE60'));
            // } elseif ($percentage < 60) {
            //     $sheet->getStyle('G' . $row)
            //         ->getFont()
            //         ->setColor(new Color('C0392B'));
            // }

            $row++;
        }

        // Agregar pie de página
        $footerRow = $lastRow + 1;
        $sheet->mergeCells('A' . $footerRow . ':G' . $footerRow);
        $sheet->setCellValue('A' . $footerRow, 'Reporte generado el ' . now()->format('d/m/Y H:i:s'));
        
        $footerStyle = $sheet->getStyle('A' . $footerRow);
        $footerStyle->getFont()
            ->setSize(9)
            ->setColor(new Color('7F8C8D'));
        
        $footerStyle->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Ajustar altura de las filas de datos
        for ($row = 4; $row <= $lastRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        return [];
    }
}
