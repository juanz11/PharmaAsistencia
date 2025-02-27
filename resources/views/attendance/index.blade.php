@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado con gradiente -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold mb-2">Registro de Asistencia</h2>
            <p class="text-blue-100">{{ now()->format('l, d \d\e F \d\e Y') }}</p>
            <div class="mt-4 text-3xl font-bold" id="current-time">
                {{ now()->format('H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="max-w-4xl mx-auto mt-8">
        @if($todayAttendance)
            <!-- Si ya hay registro de asistencia -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-4">
                    <div class="inline-flex items-center justify-center bg-green-100 rounded-full p-2 mb-2">
                        <svg class="w-6 h-6 text-green-600" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">¡Asistencia Registrada!</h3>
                    <p class="text-gray-600 mt-1">Has registrado tu asistencia hoy a las {{ $todayAttendance->check_in_time->format('h:i A') }}</p>
                </div>

                @if($todayAttendance->notes)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Notas:</h4>
                        <p class="text-gray-600 text-sm">{{ $todayAttendance->notes }}</p>
                    </div>
                @endif
            </div>
        @else
            <!-- Si no hay registro de asistencia -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Registrar Asistencia</h3>
                    <p class="text-gray-600 mt-1">Registra tu asistencia del día de hoy</p>
                </div>

                <form action="{{ route('attendance.check-in') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas (opcional)</label>
                        <textarea id="notes" name="notes" rows="3" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Agregar notas..."></textarea>
                    </div>
                    <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-medium text-white bg-[#1F4591] hover:bg-[#163670] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1F4591] transition-all duration-200 transform hover:scale-[1.02]">
                        <svg class="w-5 h-5 mr-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Registrar Entrada
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
    function updateClock() {
        const clockElement = document.getElementById('current-time');
        const now = new Date();
        clockElement.textContent = now.toLocaleTimeString();
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection
