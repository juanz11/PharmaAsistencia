@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-custom py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl overflow-hidden">
            <!-- Encabezado con gradiente -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-white mb-2">Bienvenido, {{ Auth::user()->name }}</h2>
                    <p class="text-blue-100">{{ now()->format('l, d \d\e F \d\e Y') }}</p>
                    <div class="mt-4 text-3xl font-bold text-white" id="current-time">
                        {{ now()->format('H:i:s') }}
                    </div>
                </div>
            </div>

            <!-- Contenido -->
            <div class="p-8">
                <!-- Tarjeta de Registro -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Registro de Asistencia</h3>
                            <span class="px-4 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $attendance ? 'Registrado' : 'Pendiente' }}
                            </span>
                        </div>

                        @if(!$attendance)
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
                                    class="w-full flex justify-center items-center py-3 px-4 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Registrar Entrada
                                </button>
                            </form>
                        @else
                            <div class="text-center">
                                <div class="mb-4">
                                    <svg class="w-12 h-12 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">¡Asistencia Registrada!</h4>
                                <p class="text-gray-600">Has registrado tu entrada a las {{ $attendance->check_in_time->format('H:i:s') }}</p>
                                @if($attendance->notes)
                                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                        <p class="text-gray-700 whitespace-pre-line">{{ $attendance->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

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
