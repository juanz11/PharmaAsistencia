<!-- Ticket de Asistencia -->
<div class="w-[300px] h-[700px] mx-auto">
    <div class="h-full bg-white rounded-lg shadow-lg overflow-hidden border-2 border-[#1F4591] hover:shadow-xl transition-shadow duration-300">
        <!-- Encabezado del Ticket -->
        <div class="relative bg-white p-2 text-center">
            <!-- Círculos decorativos del ticket -->
            <div class="absolute -left-2 -bottom-2 w-4 h-4 bg-white rounded-full border border-[#1F4591]"></div>
            <div class="absolute -right-2 -bottom-2 w-4 h-4 bg-white rounded-full border border-[#1F4591]"></div>
            
            <h3 class="text-[#1F4591] text-sm font-bold">Registro de Asistencia</h3>
            <span class="text-[#1F4591] text-xs bg-blue-50 px-2 py-0.5 rounded-full inline-block border border-[#1F4591]/20">
                {{ now()->format('d/m/Y') }}
            </span>
        </div>

        <!-- Contenido del Ticket -->
        <div class="p-2 bg-white">
            <!-- Línea decorativa estilo ticket -->
            <div class="border-b border-dashed border-[#1F4591] -mx-2 mb-2"></div>

            <!-- Información del Registro -->
            <div class="space-y-2">
                @if($attendance)
                    <!-- Estado del Registro -->
                    <div class="text-center">
                        <div class="inline-block">
                            <div class="w-5 h-5 mx-auto mb-1 bg-gradient-to-br from-green-400 to-green-600 rounded-full p-1 shadow-sm shadow-green-200 flex items-center justify-center">
                                <svg class="w-3 h-3" viewBox="0 0 24 24">
                                    <path 
                                        stroke="#ffffff" 
                                        stroke-width="3"
                                        fill="none"
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        d="M5 13l4 4L19 7"
                                    ></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-green-700 font-bold text-xs">Entrada Registrada</h4>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full inline-block">
                                    {{ $attendance->check_in_time->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles -->
                    <div class="grid grid-cols-2 gap-1">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-1.5 rounded text-center border border-[#1F4591]/20">
                            <span class="text-blue-500 text-[10px] block">Estado</span>
                            <span class="text-[#1F4591] font-bold text-xs">{{ ucfirst($attendance->status) }}</span>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-1.5 rounded text-center border border-[#1F4591]/20">
                            <span class="text-blue-500 text-[10px] block">Dispositivo</span>
                            <span class="text-[#1F4591] font-bold text-xs">{{ $attendance->check_in_device }}</span>
                        </div>
                    </div>

                    @if($attendance->notes)
                        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-1.5 rounded text-center border border-yellow-200">
                            <span class="text-yellow-600 text-[10px] block">Notas:</span>
                            <p class="text-gray-700 text-xs">{{ $attendance->notes }}</p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Línea decorativa final -->
            <div class="border-b border-dashed border-[#1F4591] -mx-2 mt-2"></div>
        </div>

        <!-- Pie del ticket -->
        <div class="bg-gradient-to-b from-blue-50 to-white px-2 py-1 text-center">
            <span class="text-blue-600 text-[10px] font-medium">¡Que tengas un excelente día!</span>
        </div>
    </div>
</div>
