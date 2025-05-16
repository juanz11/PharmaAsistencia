<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class AttendanceReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return new Collection($this->data);
    }

    public function headings(): array
    {
        return [
            'Empleado',
            'Días Trabajados',
            'Horas Trabajadas',
            'Dispositivo más usado'
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->total_days,
            $row->total_hours,
            $row->device
        ];
    }
}
