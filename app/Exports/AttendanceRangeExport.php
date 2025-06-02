<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class AttendanceRangeExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
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
            // Get user's attendances within date range
            $attendances = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$this->startDate, $this->endDate])
                ->get();

            if ($attendances->isEmpty()) {
                // If no attendance records, add a "No marcó" record
                $data->push([
                    'user' => $user,
                    'attendance' => null,
                    'date' => $this->startDate->copy()
                ]);
            } else {
                // Add each attendance record
                foreach ($attendances as $attendance) {
                    $data->push([
                        'user' => $user,
                        'attendance' => $attendance,
                        'date' => Carbon::parse($attendance->date)
                    ]);
                }
            }
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

        if (!$attendance) {
            return [
                $user->name,
                $date->format('d/m/Y'),
                'No marcó',
                'No marcó',
                'No marcó',
                'No marcó',
                'Ausente'
            ];
        }

        return [
            $user->name,
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

    public function title(): string
    {
        return 'Reporte de Asistencias';
    }
}
