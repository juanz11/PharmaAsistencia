@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-700 rounded-t-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold  mb-2">Control de Asistencias</h2>
            <p class="text-gray-300">Gestión y monitoreo de asistencias de empleados</p>
        </div>

        <!-- Panel de Filtros -->
        <div class="bg-gray-800 p-6 border-b border-gray-700">
            <form action="{{ route('admin.attendances.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Fecha Inicio</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="bg-gray-700  rounded-lg border-gray-600 w-full focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Fecha Fin</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                           class="bg-gray-700  rounded-lg border-gray-600 w-full focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Empleado</label>
                    <select name="user_id" class="bg-gray-700  rounded-lg border-gray-600 w-full focus:ring-yellow-500 focus:border-yellow-500">
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
                            class="w-full bg-yellow-600 hover:bg-yellow-700 font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de Asistencias -->
        <div class="bg-gray-800 shadow-lg rounded-b-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
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
                        @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                {{ $attendance->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                {{ optional($attendance->created_at)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                {{ optional($attendance->check_in_at)->format('g:i:s A') ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                {{ optional($attendance->check_out_at)->format('g:i:s A') ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attendance->status === 'present')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-800 text-green-100">
                                        Presente
                                    </span>
                                @elseif($attendance->status === 'absent')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-800 text-red-100">
                                        Ausente
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-800 text-yellow-100">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                {{ $attendance->device ?? 'No registrado' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                No hay registros de asistencia para mostrar
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="px-6 py-4 bg-gray-800">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
