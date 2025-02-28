@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Mi Historial de Asistencias</h2>
        </div>

        <!-- Filtro de fecha -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <form action="{{ route('attendance.list') }}" method="GET" class="flex flex-wrap gap-4 items-end">
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
                <button type="submit" class="bg-[#1F4591] text-white px-4 py-2 rounded-md hover:bg-[#163670] flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Buscar
                </button>
            </form>
        </div>

        <!-- Tabla de Asistencias -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3">Fecha</th>
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3 pr-12">Entrada</th>
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3 pl-12">Salida</th>
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3">Estado</th>
                        <th class="text-left text-sm font-semibold text-gray-600 uppercase px-6 py-3">Dispositivo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $attendance->date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 pr-12">
                            {{ $attendance->check_in_time ? $attendance->check_in_time->format('g:i:s A') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 pl-12">
                            {{ $attendance->check_out_time ? $attendance->check_out_time->format('g:i:s A') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($attendance->status === 'present')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Presente
                                </span>
                            @elseif($attendance->status === 'absent')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Ausente
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $attendance->check_in_device ?: 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron registros para el período seleccionado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection
