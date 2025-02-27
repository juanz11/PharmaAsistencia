<!-- Ticket de Asistencia -->
<div class="max-w-[280px] mx-auto">
    <div class="bg-gradient-to-b from-white to-blue-50 rounded-lg shadow-lg overflow-hidden border-t-8 border-[#1F4591] hover:shadow-xl transition-shadow duration-300">
        <!-- Encabezado del Ticket con diseño festivo -->
        <div class="relative bg-gradient-to-r from-[#1F4591] to-[#163670] p-4 text-center">
            <!-- Círculos decorativos del ticket -->
            <div class="absolute -left-3 -bottom-3 w-6 h-6 bg-white rounded-full"></div>
            <div class="absolute -right-3 -bottom-3 w-6 h-6 bg-white rounded-full"></div>
            
            <h3 class="text-white text-lg font-bold mb-1">Registro de Asistencia</h3>
            <span class="text-blue-100 text-sm bg-blue-800/30 px-3 py-1 rounded-full inline-block">
                {{ now()->format('d/m/Y') }}
            </span>
        </div>

        <!-- Contenido del Ticket -->
        <div class="p-4 bg-white">
            <!-- Línea decorativa estilo ticket -->
            <div class="border-b-2 border-dashed border-blue-200 -mx-4 mb-4"></div>

            <!-- Información del Registro -->
            <div class="space-y-4">
                @if($attendance)
                    <!-- Estado del Registro con diseño mejorado -->
                    <div class="text-center">
                        <div class="inline-block">
                            <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-green-400 to-green-600 rounded-full p-4 shadow-lg shadow-green-200 flex items-center justify-center">
                                <svg class="w-10 h-10" viewBox="0 0 24 24">
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
                            <div class="space-y-1">
                                <h4 class="text-green-700 font-bold text-sm">Entrada Registrada</h4>
                                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full inline-block">
                                    {{ $attendance->check_in_time->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles con diseño mejorado -->
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-3 rounded-xl text-center border border-blue-100">
                            <span class="text-blue-500 text-xs block mb-1">Estado</span>
                            <span class="text-[#1F4591] font-bold text-sm">{{ ucfirst($attendance->status) }}</span>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-3 rounded-xl text-center border border-blue-100">
                            <span class="text-blue-500 text-xs block mb-1">Dispositivo</span>
                            <span class="text-[#1F4591] font-bold text-sm">{{ $attendance->check_in_device }}</span>
                        </div>
                    </div>

                    @if($attendance->notes)
                        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-3 rounded-xl text-center border border-yellow-100">
                            <span class="text-yellow-600 text-xs font-medium block mb-1">Notas:</span>
                            <p class="text-gray-700 text-sm">{{ $attendance->notes }}</p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Línea decorativa final -->
            <div class="border-b-2 border-dashed border-blue-200 -mx-4 mt-4"></div>
        </div>

        <!-- Pie del ticket con diseño festivo -->
        <div class="bg-gradient-to-b from-blue-50 to-white px-4 py-2 text-center">
            <span class="text-blue-600 text-xs font-medium">¡Que tengas un excelente día!</span>
        </div>
    </div>
</div>
