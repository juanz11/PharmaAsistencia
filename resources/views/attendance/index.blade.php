@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-gradient-to-r from-[#1a4175] to-[#1F4591] rounded-t-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold   mb-2">Mi Registro de Asistencia</h2>
            <p class="text-blue-100">Registro y control de tus horarios</p>
        </div>

        <!-- Panel de Check-in/Check-out -->
        <div class="bg-white border-x border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Check-in -->
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-lg p-6 shadow-md border border-gray-100">
                    <h3 class="text-lg font-semibold text-[#1a4175] mb-4 flex items-center">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Entrada
                    </h3>
                    @if(!$todayAttendance || !$todayAttendance->check_in)
                        <form action="{{ route('attendance.check-in') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-[#1F4591] hover:bg-[#1a4175]  font-bold py-3 px-4 rounded-lg transition duration-150 ease-in-out flex items-center justify-center shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="240" height="240">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Registrar Entrada
                            </button>
                        </form>
                    @else
                        <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                            <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="240" height="240">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-green-600 font-semibold block">Entrada registrada</span>
                            <span class="text-green-500 text-sm">{{ $todayAttendance->check_in->format('g:i:s A') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Check-out -->
                <div class="bg-gradient-to-br from-indigo-50 to-white rounded-lg p-6 shadow-md border border-gray-100">
                    <h3 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="240" height="240">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Salida
                    </h3>
                    @if($todayAttendance && $todayAttendance->check_in && !$todayAttendance->check_out)
                        <form action="{{ route('attendance.check-out') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-indigo-600 hover:bg-indigo-700  font-bold py-3 px-4 rounded-lg transition duration-150 ease-in-out flex items-center justify-center shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="240" height="240">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Registrar Salida
                            </button>
                        </form>
                    @elseif($todayAttendance && $todayAttendance->check_out)
                        <div class="text-center p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                            <svg class="w-8 h-8 text-indigo-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="240" height="240">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-indigo-600 font-semibold block">Salida registrada</span>
                            <span class="text-indigo-500 text-sm">{{ $todayAttendance->check_out->format('g:i:s A') }}</span>
                        </div>
                    @else
                        <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="text-gray-500">Primero debes registrar tu entrada</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Historial de Asistencias -->
        <div class="bg-white shadow-lg rounded-b-lg overflow-hidden border-x border-b border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="240" height="240">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Historial de Asistencias
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrada</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salida</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dispositivo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendances as $attendance)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->check_in ? $attendance->check_in->format('g:i:s A') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->check_out ? $attendance->check_out->format('g:i:s A') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 
                                       ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($attendance->device === 'Mobile')
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        Móvil
                                    </span>
                                @else
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
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
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
