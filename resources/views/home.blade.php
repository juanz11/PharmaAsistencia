@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-sm mx-auto">
        <div class="bg-white/90 backdrop-blur-lg rounded-xl shadow-lg">
            <!-- Encabezado -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-5 py-3 rounded-t-xl">
                <div class="text-center">
                    <h2 class="text-lg font-semibold text-white">{{ Auth::user()->name }}</h2>
                    <p class="text-xs text-blue-100 mt-1">{{ now()->format('l, d \d\e F \d\e Y') }}</p>
                    <div class="mt-1 text-xl font-bold text-white" id="current-time">
                        {{ now()->format('H:i:s') }}
                    </div>
                </div>
            </div>

            <!-- Contenido -->
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-900">Registro de Asistencia</h3>
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $attendance ? 'Registrado' : 'Pendiente' }}
                    </span>
                </div>

                @if(!$attendance)
                    <form action="{{ route('attendance.check-in') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label for="notes" class="block text-xs font-medium text-gray-700 mb-1">
                                Notas (opcional)
                            </label>
                            <textarea name="notes" id="notes" rows="2" 
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Agregar notas..."></textarea>
                        </div>
                        <button type="submit" 
                            class="w-full flex justify-center items-center py-2 px-3 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Registrar Entrada
                        </button>
                    </form>
                @else
                    <div class="text-center">
                        <div class="mb-2">
                            <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 mb-1">¡Asistencia Registrada!</h4>
                        <p class="text-xs text-gray-600">Has registrado tu entrada a las {{ $attendance->check_in_time->format('H:i:s') }}</p>
                        @if($attendance->notes)
                            <div class="mt-2 p-2 bg-gray-50 rounded-lg text-xs">
                                <p class="text-gray-700 whitespace-pre-line">{{ $attendance->notes }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                @if(session('success'))
                    <div class="mt-3 p-2 rounded-lg bg-green-50 border border-green-200">
                        <p class="text-xs text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-3 p-2 rounded-lg bg-red-50 border border-red-200">
                        <p class="text-xs text-red-800">{{ session('error') }}</p>
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
