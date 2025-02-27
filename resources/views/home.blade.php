@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4">
    @if(auth()->user()->role === 'admin')
        <!-- Panel de Administrador -->
        <div class="mb-6">
            <div class="bg-gradient-to-r from-[#1a4175] to-[#2563eb] rounded-lg shadow-lg p-4">
                <h2 class="text-xl font-bold mb-1">¡Bienvenido al Panel de Administración!</h2>
                <p class="text-base opacity-90">Desde aquí puedes gestionar los empleados y supervisar la asistencia del personal.</p>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-4">
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-1">Empleados Activos</h3>
                    <p class="text-2xl font-bold text-[#1a4175]">{{ \App\Models\User::where('role', 'employee')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-1">Registros Hoy</h3>
                    <p class="text-2xl font-bold text-[#1a4175]">{{ \App\Models\Attendance::whereDate('check_in_time', today())->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-1">Personal Presente</h3>
                    <p class="text-2xl font-bold text-[#1a4175]">{{ \App\Models\Attendance::whereDate('check_in_time', today())->whereNull('check_out')->count() }}</p>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Acciones Rápidas</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <a href="{{ route('admin.users.index') }}" class="bg-white rounded-lg shadow p-3 hover:shadow-lg transition-shadow flex items-center">
                        <div class="bg-white rounded-full p-2 mr-3">
                            <svg class="w-4 h-4 text-[#1F4591]" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm text-gray-800">Gestionar Empleados</h4>
                            <p class="text-xs text-gray-600">Administrar usuarios</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('attendance.index') }}" class="bg-white rounded-lg shadow p-3 hover:shadow-lg transition-shadow flex items-center">
                        <div class="bg-white rounded-full p-2 mr-3">
                            <svg class="w-4 h-4 text-[#1F4591]" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm text-gray-800">Ver Asistencias</h4>
                            <p class="text-xs text-gray-600">Consultar registros</p>
                        </div>
                    </a>
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
@endsection