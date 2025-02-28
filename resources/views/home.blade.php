@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @if(auth()->user()->role === 'admin')
            <!-- Panel de Administrador -->
            <div class="space-y-6">
                <!-- Encabezado con efecto de gradiente y textura -->
                <div class="relative overflow-hidden bg-gradient-to-r from-blue-700 to-blue-500 rounded-xl shadow-2xl p-8 text-center">
                    <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:20px_20px]"></div>
                    <div class="relative">
                        <h2 class="text-3xl font-bold text-white mb-2">¡Bienvenido al Panel de Administración!</h2>
                        <p class="text-blue-100 text-lg">Gestiona y supervisa la asistencia del personal desde aquí</p>
                        <div class="flex justify-center mt-4 text-center">
                            <svg class="h-[200px] w-[200px] text-white/30 text-center" width="240" height="240" fill="currentColor" viewBox="0 0 300 300">
                                <path d="M150 180l112.5-62.55L150 55.05l-112.5 62.4L150 180z"/>
                                <path d="M150 180l77-42.75c3 26.25 1.5 53.25-8.25 81A149.4 149.4 0 01150 250.65a149.4 149.4 0 01-85.35-37.5 151.05 151.05 0 01-8.25-81L150 180z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Aquí puedes agregar tarjetas de estadísticas rápidas -->
                </div>

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
                                        <div class="bg-gradient-to-br from-blue-700 to-blue-500 rounded-lg p-3 mb-4 hover:scale-105 transition-transform">
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
                                        <div class="bg-gradient-to-br from-blue-700 to-blue-500 rounded-lg p-3 mb-4 hover:scale-105 transition-transform">
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
            <div class="space-y-6">
                <!-- Encabezado -->
                <div class="bg-gradient-to-r from-blue-700 to-blue-500 rounded-t-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-white mb-2">Mi Registro de Asistencia</h2>
                    <p class="text-blue-100">Registro y control de tus horarios</p>
                </div>

                <!-- Panel de Check-in/Check-out -->
                <div class="bg-white border-x border-gray-200 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tarjeta de Entrada -->
                        <div class="bg-gradient-to-br from-blue-50 to-white rounded-lg p-6 shadow-md border border-gray-100">
                            <h3 class="text-lg font-semibold text-blue-700 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Entrada
                            </h3>
                            @if(!$todayAttendance)
                                <form action="{{ route('attendance.check-in') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-lg transition duration-150 ease-in-out flex items-center justify-center shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                        Registrar Entrada
                                    </button>
                                </form>
                            @else
                                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                    <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-green-600 font-semibold block">Entrada registrada</span>
                                    <span class="text-green-500 text-sm">{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('g:i:s A') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Tarjeta de Salida -->
                        <div class="bg-gradient-to-br from-indigo-50 to-white rounded-lg p-6 shadow-md border border-gray-100">
                            <h3 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Salida
                            </h3>
                            @if($todayAttendance && !$todayAttendance->check_out_time)
                                <form action="{{ route('attendance.check-out') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-150 ease-in-out flex items-center justify-center shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Registrar Salida
                                    </button>
                                </form>
                            @elseif($todayAttendance && $todayAttendance->check_out_time)
                                <div class="text-center p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                                    <svg class="w-8 h-8 text-indigo-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-indigo-600 font-semibold block">Salida registrada</span>
                                    <span class="text-indigo-500 text-sm">{{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('g:i:s A') }}</span>
                                </div>
                            @else
                                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <span class="text-gray-600">Primero debes registrar tu entrada</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Historial -->
                <div class="bg-white rounded-b-lg shadow-lg overflow-hidden border-x border-b border-gray-200">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <a href="{{ route('attendance.list') }}" class="flex items-center justify-center text-blue-700 hover:text-blue-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ver mi historial de asistencias
                        </a>
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
        document.getElementById('dot0').className = `w-3 h-3 rounded-full transition-all duration-300 ${index === 0 ? 'bg-blue-700' : 'bg-gray-300'}`;
        document.getElementById('dot1').className = `w-3 h-3 rounded-full transition-all duration-300 ${index === 1 ? 'bg-blue-700' : 'bg-gray-300'}`;
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
