@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @if(auth()->user()->role === 'admin')
            <!-- Panel de Administrador -->
            <div class="space-y-6">
                <!-- Encabezado con efecto de gradiente y textura -->
                <div class="relative overflow-hidden bg-gradient-to-r from-[#1a4175] to-[#2563eb] rounded-xl shadow-2xl p-8 text-center">
                    <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:20px_20px]"></div>
                    <div class="relative">
                        <h2 class="text-3xl font-bold text-white mb-2">¡Bienvenido al Panel de Administración!</h2>
                        <p class="text-blue-100 text-lg">Gestiona y supervisa la asistencia del personal desde aquí</p>
                        <div class="flex justify-center mt-4 text-center">
                            <svg class="h-[200px] w-[200px] text-white/30 text-center" width="240" height="240" fill="currentColor" viewBox="0 0 300 300" style="
    text-align: center!important;!importan;!importa;!import;!impor;!impo;!imp;!im;!i;!;
    display: inline;
">
                                <path d="M150 180l112.5-62.55L150 55.05l-112.5 62.4L150 180z"/>
                                <path d="M150 180l77-42.75c3 26.25 1.5 53.25-8.25 81A149.4 149.4 0 01150 250.65a149.4 149.4 0 01-85.35-37.5 151.05 151.05 0 01-8.25-81L150 180z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                   
                    
                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4 text-center">
                        <h3 class="text-lg font-semibold text-gray-900">Acciones Rápidas</h3>
                        <p class="text-sm text-gray-500">Gestiona usuarios y asistencias con un solo clic</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-center">
                            <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-4xl">
                                <div class="flex justify-center space-x-8">
                                    <a href="{{ route('admin.users.index') }}" class="w-64 text-center">
                                        <div class="bg-gradient-to-br from-[#1a4175] to-[#2563eb] rounded-lg p-3 mb-4 hover:scale-105 transition-transform">
                                            <svg class="h-[150px] w-[150px] mx-auto" width="300" height="300" fill="none" stroke="currentColor" viewBox="0 0 300 300">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="12" 
                                                      d="M150 54.45a49.95 49.95 0 110 66.15M187.5 262.5H37.5v-12.45a75 75 0 01150 0v12.45zm0 0h75v-12.45a75 75 0 00-112.5-64.95M162.45 87.45a49.95 49.95 0 11-99.9 0 49.95 49.95 0 0199.9 0z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Gestionar Empleados</h4>
                                        <p class="mt-1 text-sm text-gray-500">Administrar usuarios y permisos del sistema</p>
                                    </a>
                                    
                                    <div class="h-[150px] w-px bg-gray-200 self-center"></div>
                                    
                                    <a href="{{ route('attendance.index') }}" class="w-64 text-center">
                                        <div class="bg-gradient-to-br from-[#1a4175] to-[#2563eb] rounded-lg p-3 mb-4 hover:scale-105 transition-transform">
                                            <svg class="h-[150px] w-[150px] mx-auto" width="300" height="300" fill="none" stroke="currentColor" viewBox="0 0 300 300">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="12" 
                                                      d="M112.5 62.55H87.45a25.05 25.05 0 00-24.9 24.9v150a25.05 25.05 0 0024.9 25.05h125.1a25.05 25.05 0 0025.05-25.05v-150a25.05 25.05 0 00-25.05-24.9H187.5M112.5 62.55a25.05 25.05 0 0025.05 24.9h24.9a25.05 25.05 0 0025.05-24.9M112.5 62.55a25.05 25.05 0 0125.05-25.05h24.9a25.05 25.05 0 0125.05 25.05"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Control de Asistencia</h4>
                                        <p class="mt-1 text-sm text-gray-500">Monitorear registros y horarios del personal</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Panel de Empleado -->
            <div class="max-w-3xl mx-auto">
                <!-- Encabezado Simple -->
                <div class="text-center mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800">Registro de Asistencia</h2>
                    <p class="text-gray-600 mt-2">{{ now()->format('l, d \d\e F Y') }}</p>
                </div>

                <!-- Panel de Marcación -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Botón de Entrada -->
                        <div class="text-center">
                            @if(!$todayAttendance)
                                <form action="{{ route('attendance.check-in') }}" method="POST" class="relative">
                                    @csrf
                                    <button type="submit" class="w-full bg-white hover:bg-gray-50 text-blue-500 py-4 px-6 rounded-lg transition-all duration-200 group border-2 border-blue-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-8 h-8 mb-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                            </svg>
                                            <span class="text-lg font-medium">Marcar Entrada</span>
                                        </div>
                                    </button>
                                </form>
                            @else
                                <div class="relative">
                                    <div class="bg-green-50 rounded-lg p-4 border-2 border-green-200">
                                        <div class="flex flex-col items-center">
                                            <div class="bg-green-100 p-2 rounded-full mb-2">
                                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <span class="text-green-800 font-medium">Entrada Registrada</span>
                                            <span class="text-green-600 text-sm mt-1">{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Botón de Salida -->
                        <div class="text-center">
                            @if($todayAttendance && !$todayAttendance->check_out_time)
                                <form action="{{ route('attendance.check-out') }}" method="POST" class="relative">
                                    @csrf
                                    <button type="submit" class="w-full bg-white hover:bg-gray-50 text-indigo-500 py-4 px-6 rounded-lg transition-all duration-200 group border-2 border-indigo-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-8 h-8 mb-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            <span class="text-lg font-medium">Marcar Salida</span>
                                        </div>
                                    </button>
                                </form>
                            @elseif($todayAttendance && $todayAttendance->check_out_time)
                                <div class="relative">
                                    <div class="bg-indigo-50 rounded-lg p-4 border-2 border-indigo-200">
                                        <div class="flex flex-col items-center">
                                            <div class="bg-indigo-100 p-2 rounded-full mb-2">
                                                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <span class="text-indigo-800 font-medium">Salida Registrada</span>
                                            <span class="text-indigo-600 text-sm mt-1">{{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-100 rounded-lg p-4 border-2 border-gray-200">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <span class="text-gray-500 font-medium">Marque entrada primero</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Mensaje del día -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">¡Que tengas un excelente día!</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    let currentSlide = 0;
    const carousel = document.getElementById('carousel');
    
    function moveCarousel(index) {
        currentSlide = index;
        carousel.style.transform = `translateX(-${index * 100}%)`;
        
        // Actualizar los dots
        document.getElementById('dot0').className = `w-3 h-3 rounded-full transition-all duration-300 ${index === 0 ? 'bg-[#1a4175]' : 'bg-gray-300'}`;
        document.getElementById('dot1').className = `w-3 h-3 rounded-full transition-all duration-300 ${index === 1 ? 'bg-[#1a4175]' : 'bg-gray-300'}`;
    }

    // Auto-rotación cada 5 segundos
    setInterval(() => {
        currentSlide = (currentSlide + 1) % 2;
        moveCarousel(currentSlide);
    }, 5000);
</script>
@endpush

<style>
.bg-grid-white {
    background-image: linear-gradient(to right, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                      linear-gradient(to bottom, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}

/* Estilos del Carrusel */
#carousel {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

#carousel > div {
    min-width: 100%;
    padding: 0 1rem;
}

.card-hover {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>
@endsection