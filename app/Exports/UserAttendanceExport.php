<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class UserAttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Hora de Entrada',
            'Hora de Salida',
            'Horas Trabajadas'
        ];
    }

    public function map($attendance): array
    {
        $checkIn = $attendance->check_in ? Carbon::parse($attendance->check_in) : null;
        $checkOut = $attendance->check_out ? Carbon::parse($attendance->check_out) : null;
        $hoursWorked = ($checkIn && $checkOut) ? $checkIn->diffInHours($checkOut) : '-';

        return [
            $attendance->date ? Carbon::parse($attendance->date)->format('d/m/Y') : $attendance->created_at->format('d/m/Y'),
            $checkIn ? $checkIn->format('H:i') : '-',
            $checkOut ? $checkOut->format('H:i') : '-',
            $hoursWorked
        ];
    }
}
