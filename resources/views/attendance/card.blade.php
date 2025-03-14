<!-- Ticket de Asistencia -->
<div style="max-width: 300px; max-height: 700px;" class="w-[300px] h-[700px] mx-auto overflow-hidden">
    <div class="w-full h-full bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300" style="max-width: 300px; max-height: 700px;">
        <!-- Encabezado del Ticket -->
        <div class="relative bg-white p-2 text-center">
            <!-- Círculos decorativos del ticket -->
            <div class="absolute -left-2 -bottom-2 w-4 h-4 bg-white rounded-full border border-gray-300"></div>
            <div class="absolute -right-2 -bottom-2 w-4 h-4 bg-white rounded-full border border-gray-300"></div>
            
            <h3 class="text-[#1F4591] text-sm font-bold truncate">Registro de Asistencia</h3>
            <span class="text-[#1F4591] text-xs bg-blue-50 px-2 py-0.5 rounded-full inline-block border border-gray-200">
                {{ now()->format('d/m/Y') }}
            </span>
        </div>

        <!-- Contenido del Ticket -->
        <div class="p-2 bg-white">
            <!-- Línea decorativa estilo ticket -->
            <div class="border-b border-dashed border-gray-300 -mx-2 mb-2"></div>

            <!-- Información del Registro -->
            <div class="space-y-2">
                @if($attendance)
                    <!-- Estado del Registro -->
                    <div class="text-center">
                        <div class="inline-block">
                            <div class="w-6 h-6 mx-auto mb-1 bg-gradient-to-br from-green-400 to-green-600 rounded-full p-1 shadow shadow-green-200 flex items-center justify-center border border-green-300">
                                <svg class="w-4 h-4" viewBox="0 0 24 24">
                                    <path 
                                        stroke="#22c55e" 
                                        stroke-width="3"
                                        fill="none"
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        d="M5 13l4 4L19 7"
                                    ></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-green-700 font-bold text-xs truncate">Entrada Registrada</h4>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full inline-block border border-green-200">
                                    {{ $attendance->check_in_time->venezuelaFormat() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles -->
                    <div class="grid grid-cols-2 gap-1">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-1.5 rounded text-center border border-gray-200">
                            <span class="text-blue-500 text-[10px] block truncate">Estado</span>
                            <span class="text-[#1F4591] font-bold text-xs truncate">{{ ucfirst($attendance->status) }}</span>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-1.5 rounded text-center border border-gray-200">
                            <span class="text-blue-500 text-[10px] block truncate">Dispositivo</span>
                            <span class="text-[#1F4591] font-bold text-xs truncate">{{ $attendance->check_in_device }}</span>
                        </div>
                    </div>

                    @if($attendance->notes)
                        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-1.5 rounded text-center border border-yellow-200">
                            <span class="text-yellow-600 text-[10px] block truncate">Notas:</span>
                            <p class="text-gray-700 text-xs line-clamp-2">{{ $attendance->notes }}</p>
                        </div>
                    @endif
                @else
                    <!-- No hay registro, mostrar botón de entrada -->
                    <div class="text-center space-y-2">
                        <div class="inline-block">
                            <div class="w-12 h-12 mx-auto mb-2 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full p-2 shadow shadow-blue-200 flex items-center justify-center border border-blue-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">No has registrado tu entrada hoy</p>
                        </div>
                        <form action="{{ route('attendance.check-in') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-[#1a4175] to-[#2563eb]  font-semibold py-2 px-4 rounded-lg shadow hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2">
                                <span>Registrar Entrada</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Línea decorativa final -->
            <div class="border-b border-dashed border-gray-300 -mx-2 mt-2"></div>
        </div>

        <!-- Pie del ticket -->
        <div class="bg-gradient-to-b from-blue-50 to-white px-2 py-1 text-center">
            <span class="text-blue-600 text-[10px] font-medium">¡Que tengas un excelente día!</span>
        </div>
    </div>
</div>
