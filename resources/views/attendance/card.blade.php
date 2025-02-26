<div class="max-w-[250px] mx-auto">
    <div class="bg-white/95 backdrop-blur-lg rounded-lg shadow overflow-hidden">
        <!-- Encabezado -->
        <div class="bg-[#1a4175] px-2 py-0.5">
            <div class="text-center">
                <p class="text-[9px] text-gray-200 leading-tight">{{ now()->locale('es')->isoFormat('dddd, D \d\e MMMM \d\e YYYY') }}</p>
                <div class="text-xs font-medium text-white leading-tight" id="current-time">
                    {{ now()->format('H:i:s') }}
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-2">
            @if(!isset($attendance) || !$attendance)
                <div class="flex items-center justify-between mb-1.5">
                    <h3 class="text-[11px] font-medium text-gray-700">Registro de Asistencia</h3>
                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-blue-50 text-[#1a4175]">
                        Pendiente
                    </span>
                </div>
                <form action="{{ route('attendance.check-in') }}" method="POST" class="space-y-1.5">
                    @csrf
                    <div>
                        <label for="notes" class="block text-[9px] font-medium text-gray-500 mb-0.5">
                            Notas (opcional)
                        </label>
                        <textarea name="notes" id="notes" rows="1" 
                            class="w-full px-2 py-0.5 border border-gray-200 rounded text-[11px] text-gray-600 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#1a4175] focus:border-[#1a4175] transition-colors"
                            placeholder="Agregar notas..."></textarea>
                    </div>
                    <button type="submit"
                        class="w-full flex justify-center items-center py-1 px-3 rounded text-[11px] font-medium text-white bg-[#1a4175] hover:bg-[#15345d] focus:outline-none focus:ring-2 focus:ring-[#1a4175] focus:ring-offset-1 transition-all duration-200">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Registrar Entrada
                    </button>
                </form>
            @else
                <div class="flex items-center gap-1.5">
                    <div class="shrink-0">
                        <div class="inline-flex items-center justify-center bg-green-50 rounded-full p-0.5">
                            <svg class="w-2.5 h-2.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-700 font-medium leading-tight">
                            Asistencia Registrada
                        </p>
                        <p class="text-[9px] text-gray-500 leading-tight">
                            {{ $attendance->check_in_time->format('h:i A') }}
                        </p>
                    </div>
                </div>
                @if($attendance->notes)
                    <div class="mt-1.5 p-1 bg-gray-50 rounded text-[9px] border border-gray-100">
                        <p class="text-gray-600 whitespace-pre-line leading-tight">{{ $attendance->notes }}</p>
                    </div>
                @endif

                @if(!$attendance->check_out)
                    <form action="{{ route('attendance.check-out', $attendance) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit"
                            class="w-full flex justify-center items-center py-1 px-3 rounded text-[11px] font-medium text-white bg-[#1a4175] hover:bg-[#15345d] focus:outline-none focus:ring-2 focus:ring-[#1a4175] focus:ring-offset-1 transition-all duration-200">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Registrar Salida
                        </button>
                    </form>
                @else
                    <div class="mt-3 flex items-center gap-1.5">
                        <div class="shrink-0">
                            <div class="inline-flex items-center justify-center bg-blue-50 rounded-full p-0.5">
                                <svg class="w-2.5 h-2.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-gray-700 font-medium leading-tight">
                                Salida Registrada
                            </p>
                            <p class="text-[9px] text-gray-500 leading-tight">
                                {{ $attendance->check_out->format('h:i A') }}
                            </p>
                        </div>
                    </div>
                @endif
            @endif

            @if(session('success'))
                <div class="mt-1.5 p-1 rounded bg-green-50 border border-green-100">
                    <p class="text-[9px] text-green-700 leading-tight">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mt-1.5 p-1 rounded bg-red-50 border border-red-100">
                    <p class="text-[9px] text-red-700 leading-tight">{{ session('error') }}</p>
                </div>
            @endif
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
