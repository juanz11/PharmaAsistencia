@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-gradient-to-r from-[#1a4175] to-[#1F4591] rounded-lg px-6 py-4 mb-6">
        <h1 class="text-2xl font-bold ">Reportes de Asistencia</h1>
    </div>

    <!-- Filtros de fecha -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex gap-4 items-end">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                <input type="date" name="start_date" id="start_date" 
                    value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                <input type="date" name="end_date" id="end_date" 
                    value="{{ request('end_date', now()->format('Y-m-d')) }}"
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Empleado</label>
                <select name="user_id" id="user_id" 
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos los empleados</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-[#1F4591]  px-4 py-2 rounded-md hover:bg-[#163670] flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filtrar
            </button>
        </form>
    </div>

    <!-- Estadísticas Generales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        @foreach($attendanceStats as $stat)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $stat->user->name }}</h3>
            <div class="space-y-2">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Total Días:</span> {{ $stat->total_days }}
                </p>
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Días Presentes:</span> {{ $stat->present_days }}
                </p>
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Promedio Hora Entrada:</span>
                    {{ date('g:i A', $stat->avg_check_in_seconds) }}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Gráfica de Asistencia -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tendencia de Asistencia</h2>
        <canvas id="attendanceChart" height="100"></canvas>
    </div>

    <!-- Tabla Detallada -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Registro Detallado</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto" style="
            width: 100%;
        ">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3">Empleado</th>
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3">Fecha</th>
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3 pr-12">Entrada</th>
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3 pl-12">Salida</th>
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3">Estado</th>
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3">Dispositivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $attendance->user->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ optional($attendance->created_at)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 pr-12">
                            {{ optional($attendance->check_in)->format('g:i:s A') ?: '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 pl-12">
                            {{ optional($attendance->check_out)->format('g:i:s A') ?: '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($attendance->status === 'present')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Presente
                                </span>
                            @elseif($attendance->status === 'absent')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Ausente
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pendiente
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $attendance->device ?? 'No registrado' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($attendances->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500">No se encontraron registros para el período seleccionado.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const dates = @json($dailyAttendance->pluck('date'));
    const totalAttendance = @json($dailyAttendance->pluck('total_attendance'));
    const presentCount = @json($dailyAttendance->pluck('present_count'));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [
                {
                    label: 'Total Asistencias',
                    data: totalAttendance,
                    borderColor: '#1F4591',
                    backgroundColor: 'rgba(31, 69, 145, 0.1)',
                    fill: true
                },
                {
                    label: 'Presentes',
                    data: presentCount,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Tendencia de Asistencia Diaria'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
