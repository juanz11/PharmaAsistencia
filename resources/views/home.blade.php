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
                                            <svg class="h-[150px] w-[150px] mx-auto" width="150" height="150" fill="none" stroke="currentColor" viewBox="0 0 300 300">
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
                                            <svg class="h-[150px] w-[150px] mx-auto" width="150" height="150" fill="none" stroke="currentColor" viewBox="0 0 300 300">
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
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-[#1a4175] to-[#2563eb] px-6 py-4">
                        <h2 class="text-xl font-semibold  ">¡Bienvenido, {{ auth()->user()->name }}!</h2>
                        <p class=" opacity-90 mt-1">Sistema de Control de Asistencia</p>
                    </div>

                    <div class="p-6">
                        <!-- Tarjeta de Asistencia -->
                        @include('attendance.card')
                    </div>
                </div>

                <!-- Información del Usuario -->
                <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Mi Información</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Cédula de Identidad</p>
                            <p class="font-medium">{{ auth()->user()->identification }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fecha de Ingreso</p>
                            <p class="font-medium">{{ auth()->user()->join_date ? auth()->user()->join_date->format('d/m/Y') : 'No especificada' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Correo Electrónico</p>
                            <p class="font-medium">{{ auth()->user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ubicación</p>
                            <p class="font-medium">{{ auth()->user()->location }}</p>
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center text-sm text-[#1a4175] hover:text-[#15345d]">
                            Actualizar información
                            <svg class="w-4 h-4 ml-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.bg-grid-white {
    background-image: linear-gradient(to right, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                      linear-gradient(to bottom, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
}
</style>
@endsection