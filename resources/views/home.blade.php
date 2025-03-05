@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-6">
    <div class="container mx-auto px-4 max-w-5xl">
        @if(auth()->user()->role === 'admin')
            <!-- Panel de Administrador -->
            <div class="space-y-6" style="
                                display: flex;
                            ">
                <!-- Encabezado con efecto de gradiente -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 relative overflow-hidden" style="
    display: contents;
">
                    <div class="absolute inset-0 bg-grid-white/[0.05]"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold  mb-2">Panel de Administración</h2>
                            <p class="text-blue-100">Gestión y control de asistencia del personal</p>
                        </div>
                        <img src="https://sncpharma.com/wp-content/uploads/2024/11/lightbulb-1.png" alt="Logo" class="h-12 w-auto">

                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="space-y-6" style="display: inline-table;">
    <!-- Encabezado con efecto de gradiente -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-grid-white/[0.05]"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Gestion de Asistencia</h2>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="flex flex-row justify-center gap-6" style="
    height: 437px;
">
        <a href="/admin/users" class="transform transition-all duration-200 hover:scale-[1.02] flex-1">
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md border border-gray-100">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 rounded-lg p-2">
                        <svg class="w-12 h-12 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Empleados</h3>
                        <p class="text-gray-600">Gestionar personal</p>
                    </div>
                </div>
            </div>
        </a>

        <a href="/admin/reports" class="transform transition-all duration-200 hover:scale-[1.02] flex-1">
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md border border-gray-100">
                <div class="flex items-center space-x-4">
                    <div class="bg-indigo-100 rounded-lg p-2">
                        <svg class="w-12 h-12 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Asistencias</h3>
                        <p class="text-gray-600">Control de marcaciones</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
            </div>
        @else
            <!-- Panel de Empleado -->
            <div class="max-w-2xl mx-auto">
                <!-- Encabezado -->
                <div class="bg-white rounded-t-xl shadow-sm border border-gray-200 p-6" style="
    width: 615px;
    display: -webkit-box;
">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Registro de Asistencia</h2>
                            <p class="text-gray-600 mt-1">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [del] YYYY') }}</p>
                        </div>
                        <img src="https://sncpharma.com/wp-content/uploads/2024/11/lightbulb-1.png" alt="Logo" class="h-12 w-auto">

                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="flex flex-row justify-center items-center gap-12 mt-6 bg-white rounded-lg shadow-sm p-4 mx-auto" style="max-width: 800px;">
                        <!-- Hora -->
                        <div class="flex items-center gap-2">
                            <div class="bg-blue-100 rounded-full p-2">
                                <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-blue-800 font-medium">{{ now()->format('g:i A') }}</span>
                        </div>

                        <!-- Entrada -->
                        <div class="flex items-center">
                            @if(!$todayAttendance)
                                <form onsubmit="return handleAttendance(event, 'check-in')" class="flex items-center">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 bg-white hover:bg-green-50 rounded-lg p-2">
                                        <div class="bg-green-100 rounded-full p-2">
                                            <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <span class="text-green-600 font-medium">Marcar Entrada</span>
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center gap-2">
                                    <div class="bg-green-100 rounded-full p-2">
                                        <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="text-green-600 font-medium">{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('g:i A') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Salida -->
                        <div class="flex items-center">
                            @if($todayAttendance && !$todayAttendance->check_out_time)
                                <form onsubmit="return handleAttendance(event, 'check-out')" class="flex items-center">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 bg-white hover:bg-red-50 rounded-lg p-2">
                                        <div class="bg-red-100 rounded-full p-2">
                                            <svg class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        <span class="text-red-600 font-medium">Marcar Salida</span>
                                    </button>
                                </form>
                                <!-- Almuerzo -->
                                @if(!$todayAttendance->break_start)
                                <form onsubmit="return handleBreak(event, 'start')" class="flex items-center">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 bg-white hover:bg-indigo-50 rounded-lg p-2">
                                        <div class="bg-indigo-100 rounded-full p-2">
                                            <svg class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-indigo-600 font-medium">Iniciar Almuerzo</span>
                                    </button>
                                </form>
                                @elseif(!$todayAttendance->break_end)
                                <form onsubmit="return handleBreak(event, 'end')" class="flex items-center">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 bg-white hover:bg-indigo-50 rounded-lg p-2">
                                        <div class="bg-indigo-100 rounded-full p-2">
                                            <svg class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-indigo-600 font-medium">Finalizar Almuerzo</span>
                                    </button>
                                </form>
                                @else
                                <div class="flex items-center gap-2">
                                    <div class="bg-green-100 rounded-full p-2">
                                        <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="text-green-600 font-medium">Almuerzo Completado</span>
                                </div>
                                @endif
                            @elseif($todayAttendance && $todayAttendance->check_out_time)
                                <div class="flex items-center gap-2">
                                    <div class="bg-indigo-100 rounded-full p-2">
                                        <svg class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="text-indigo-600 font-medium">{{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('g:i A') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Historial -->
                        <a href="{{ route('attendance.list') }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-700">
                            <span class="font-medium">Historial</span>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <script>
                        function handleAttendance(event, type) {
                            event.preventDefault();
                            const route = type === 'check-in' ? '{{ route("attendance.check-in") }}' : '{{ route("attendance.check-out") }}';
                            const token = document.querySelector('input[name="_token"]').value;

                            fetch(route, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify({
                                    device: navigator.userAgent
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                alert(data.message);
                                if (data.success) {
                                    location.reload();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Hubo un error al procesar tu solicitud');
                            });

                            return false;
                        }

                        function handleBreak(event, type) {
                            event.preventDefault();
                            const route = type === 'start' ? '{{ route("attendance.break-start") }}' : '{{ route("attendance.break-end") }}';
                            const token = document.querySelector('input[name="_token"]').value;

                            fetch(route, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                alert(data.message);
                                if (data.success) {
                                    location.reload();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Hubo un error al procesar tu solicitud');
                            });

                            return false;
                        }
                    </script>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <!-- Hora de Entrada -->
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Hora de Entrada</h3>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $todayAttendance && $todayAttendance->check_in ? \Carbon\Carbon::parse($todayAttendance->check_in)->format('h:i A') : '-- : --' }}
                            </p>
                        </div>

                        <!-- Tiempo de Almuerzo -->
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Tiempo de Almuerzo</h3>
                            <div class="space-y-1">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Inicio:</span>
                                    {{ $todayAttendance && $todayAttendance->break_start ? \Carbon\Carbon::parse($todayAttendance->break_start)->format('h:i A') : '-- : --' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Fin:</span>
                                    {{ $todayAttendance && $todayAttendance->break_end ? \Carbon\Carbon::parse($todayAttendance->break_end)->format('h:i A') : '-- : --' }}
                                </p>
                                @if($todayAttendance && $todayAttendance->break_start && $todayAttendance->break_end)
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Duración:</span>
                                        {{ number_format(\Carbon\Carbon::parse($todayAttendance->break_start)->diffInMinutes($todayAttendance->break_end), 2) }} min
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Hora de Salida -->
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Hora de Salida</h3>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $todayAttendance && $todayAttendance->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out)->format('h:i A') : '-- : --' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 text-center text-sm text-gray-600">
                        <span class="font-medium"><strong>Tip:</strong></span><strong> Recuerda marcar tu entrada y salida diariamente </strong>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection