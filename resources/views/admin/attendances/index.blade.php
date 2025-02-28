@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-700 rounded-t-lg shadow-lg p-6">
            <h2 class="text-3xl font-bold text-white mb-2">Control de Asistencias</h2>
            <p class="text-gray-300">Gestión y monitoreo de asistencias de empleados</p>
        </div>

        <!-- Panel de Filtros -->
        <div class="bg-gray-800 p-6 border-b border-gray-700">
            <form action="{{ route('admin.attendances.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Fecha Inicio</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full bg-gray-700 text-gray-200 rounded-lg border border-gray-600 p-2 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Fecha Fin</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full bg-gray-700 text-gray-200 rounded-lg border border-gray-600 p-2 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Empleado</label>
                    <select name="user_id" class="w-full bg-gray-700 text-gray-200 rounded-lg border border-gray-600 p-2 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-200">
                        <option value="">Todos los empleados</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out transform hover:scale-105">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de Asistencias -->
        <div class="bg-gray-800 shadow-lg rounded-b-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-750">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Empleado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Entrada</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Salida</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Dispositivo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($attendances as $attendance)
                        <tr class="hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                {{ $attendance->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                {{ $attendance->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                {{ $attendance->check_in ? $attendance->check_in->format('g:i:s A') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                {{ $attendance->check_out ? $attendance->check_out->format('g:i:s A') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $attendance->status === 'present' ? 'bg-green-800 text-green-100' :
                                       ($attendance->status === 'late' ? 'bg-yellow-800 text-yellow-100' : 'bg-red-800 text-red-100') }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                @if($attendance->device === 'Mobile')
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Móvil
                                    </span>
                                @else
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        Escritorio
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="bg-gray-800 px-4 py-3 border-t border-gray-700 sm:px-6">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
