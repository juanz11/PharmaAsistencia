@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-custom py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Tarjeta Principal -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Encabezado con gradiente -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-white mb-2">Registro de Asistencia</h2>
                    <p class="text-blue-100">{{ now()->format('l, d \d\e F \d\e Y') }}</p>
                    <div class="mt-4 text-3xl font-bold text-white" id="current-time">
                        {{ now()->format('H:i:s') }}
                    </div>
                </div>
            </div>

            <!-- Contenido -->
            <div class="p-8">
                <!-- Estado actual -->
                <div class="mb-8">
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Estado del día</h3>
                        @if(!empty($attendance))
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Entrada:</span>
                                    <span class="text-gray-900 font-medium">
                                        {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i:s') : 'No registrada' }}
                                    </span>
                                </div>
                                @if($attendance->check_out_time)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Salida:</span>
                                        <span class="text-gray-900 font-medium">
                                            {{ $attendance->check_out_time->format('H:i:s') }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Estado:</span>
                                    <span class="px-4 py-1 rounded-full text-sm font-medium
                                        @if($attendance->status == 'present') bg-green-100 text-green-800
                                        @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </div>

                                @if($attendance->check_in_device)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Información del registro</h4>
                                        <div class="text-sm text-gray-600">
                                            <p>Dispositivo: {{ $attendance->check_in_device }}</p>
                                            @if($attendance->check_out_device)
                                                <p class="mt-1">Dispositivo de salida: {{ $attendance->check_out_device }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-500">No has registrado asistencia hoy</p>
                                <p class="text-sm text-gray-400 mt-1">Registra tu entrada usando el botón de abajo</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Formulario de registro -->
                <div class="space-y-4">
                    @if(!empty($attendance))
                        @if(!$attendance->check_out_time)
                            <form action="{{ route('attendance.check-out', $attendance->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Notas (opcional)
                                    </label>
                                    <textarea name="notes" id="notes" rows="2" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Agregar notas..."></textarea>
                                </div>
                                <button type="submit" 
                                    class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Registrar Salida
                                </button>
                            </form>
                        @else
                            <div class="text-center py-6 bg-gray-50 rounded-lg border border-gray-200">
                                <svg class="w-12 h-12 text-green-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">
                                    Has completado tu registro de asistencia para hoy
                                </p>
                            </div>
                        @endif
                    @else
                        <form action="{{ route('attendance.check-in') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Notas (opcional)
                                </label>
                                <textarea name="notes" id="notes" rows="2" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Agregar notas..."></textarea>
                            </div>
                            <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Registrar Entrada
                            </button>
                        </form>
                    @endif
                </div>

                @if(!empty($attendance) && $attendance->notes)
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Notas del día</h4>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-700 whitespace-pre-line">{{ $attendance->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Mensajes de estado -->
                @if(session('success'))
                    <div class="mt-6 p-4 rounded-lg bg-green-50 border border-green-200">
                        <p class="text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-6 p-4 rounded-lg bg-red-50 border border-red-200">
                        <p class="text-red-800">{{ session('error') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Actualizar el reloj cada segundo
    function updateClock() {
        const clockElement = document.getElementById('current-time');
        const now = new Date();
        clockElement.textContent = now.toLocaleTimeString();
    }

    setInterval(updateClock, 1000);
</script>
@endpush

<style>
    .bg-custom {
        background-image: url('/images/background/Background-rotated.webp');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
</style>
@endsection
