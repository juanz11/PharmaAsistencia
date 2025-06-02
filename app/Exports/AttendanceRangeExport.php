<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AttendanceRangeExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subMonth();
        $this->endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();
    }

    public function collection()
    {
        $users = User::where('role', 'employee')->get();
        $data = collect();

        foreach ($users as $user) {
            // Agregar encabezado de empleado
            $data->push([
                'user' => $user,
                'attendance' => null,
                'date' => null,
                'is_header' => true
            ]);

            $currentDate = $this->startDate->copy();
            
            // Iterar por cada día en el rango
            while ($currentDate->lte($this->endDate)) {
                // Saltar sábados y domingos
                if ($currentDate->isWeekend()) {
                    $currentDate->addDay();
                    continue;
                }

                // Buscar asistencia para este día
                $attendance = Attendance::where('user_id', $user->id)
                    ->whereDate('date', $currentDate->format('Y-m-d'))
                    ->first();

                // Agregar registro con o sin asistencia
                $data->push([
                    'user' => $user,
                    'attendance' => $attendance,
                    'date' => $currentDate->copy(),
                    'is_header' => false
                ]);

                $currentDate->addDay();
            }

            // Agregar línea en blanco entre empleados
            $data->push([
                'user' => null,
                'attendance' => null,
                'date' => null,
                'is_header' => false
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Empleado',
            'Fecha',
            'Entrada',
            'Inicio Almuerzo',
            'Fin Almuerzo',
            'Salida',
            'Estado'
        ];
    }

    public function map($row): array
    {
        $user = $row['user'];
        $attendance = $row['attendance'];
        $date = $row['date'];
        $isHeader = $row['is_header'];

        if ($isHeader) {
            return [
                $user->name,
                '',
                '',
                '',
                '',
                '',
                ''
            ];
        }

        if (!$user) {
            return [
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ];
        }

        if (!$attendance) {
            return [
                '',
                $date ? $date->format('d/m/Y') : '',
                'No marcó',
                'No marcó',
                'No marcó',
                'No marcó',
                'Ausente'
            ];
        }

        return [
            '',
            $date->format('d/m/Y'),
            $attendance->check_in ? Carbon::parse($attendance->check_in)->format('h:i A') : 'No marcó',
            $attendance->break_start ? Carbon::parse($attendance->break_start)->format('h:i A') : 'No marcó',
            $attendance->break_end ? Carbon::parse($attendance->break_end)->format('h:i A') : 'No marcó',
            $attendance->check_out ? Carbon::parse($attendance->check_out)->format('h:i A') : 'No marcó',
            $this->getAttendanceStatus($attendance)
        ];
    }

    private function getAttendanceStatus($attendance)
    {
        if (!$attendance->check_in) return 'Ausente';
        if (!$attendance->check_out) return 'En curso';
        return 'Completo';
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $currentRow = 1;

        while ($currentRow <= $lastRow) {
            $value = $sheet->getCell('A' . $currentRow)->getValue();
            
            if (!empty($value)) {
                $sheet->getStyle('A' . $currentRow . ':G' . $currentRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E2E8F0']
                    ]
                ]);
            }
            $currentRow++;
        }

        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1A4175']
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ],
        ];
    }

    public function title(): string
    {
        return 'Asistencias';
    }
}
