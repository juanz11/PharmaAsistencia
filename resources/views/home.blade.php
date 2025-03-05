@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-6">
    <div class="container mx-auto px-4 max-w-5xl">
        @if(auth()->user()->role === 'admin')
            <!-- Panel de Administrador -->
            <div class="space-y-6">
                <!-- Encabezado con efecto de gradiente -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 relative overflow-hidden">
                    <div class="absolute inset-0 bg-grid-white/[0.05]"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-2">Panel de Administración</h2>
                            <p class="text-blue-100">Gestión y control de asistencia del personal</p>
                        </div>
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto" style="width: 300px !important;">
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('admin.users.index') }}" class="transform transition-all duration-200 hover:scale-[1.02]">
                        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md border border-gray-100">
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-100 rounded-lg p-2">
                                    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 300px !important;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Empleados</h3>
                                    <p class="text-gray-600">Gestionar personal</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('attendance.index') }}" class="transform transition-all duration-200 hover:scale-[1.02]">
                        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md border border-gray-100">
                            <div class="flex items-center space-x-4">
                                <div class="bg-indigo-100 rounded-lg p-2">
                                    <svg class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 300px !important;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Asistencias</h3>
                                    <p class="text-gray-600">Control de marcaciones</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @else
            <!-- Panel de Empleado -->
            <div class="max-w-2xl mx-auto">
                <!-- Encabezado -->
                <div class="bg-white rounded-t-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Registro de Asistencia</h2>
                            <p class="text-gray-600 mt-1">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [del] YYYY') }}</p>
                        </div>
                        <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="h-12 w-auto" style="width: 300px !important;">
                    </div>

                    <div class="flex justify-between items-center mt-6 bg-white rounded-lg shadow-sm p-4 mx-auto" style="max-width: 800px;">
                        <!-- Hora -->
                        <div class="flex items-center space-x-2">
                            <div class="bg-blue-100 rounded-full p-2">
                                <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 300px !important;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-blue-800 font-medium">{{ now()->format('g:i A') }}</span>
                        </div>

                        <!-- Entrada -->
                        <div class="flex items-center">
                            @if(!$todayAttendance)
                                <form action="{{ route('attendance.check-in') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center space-x-2 bg-white hover:bg-blue-50 rounded-lg p-2">
                                        <div class="bg-blue-100 rounded-full p-2">
                                            <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 300px !important;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        <span class="text-blue-600 font-medium">Entrada</span>
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center space-x-2">
                                    <div class="bg-green-100 rounded-full p-2">
                                        <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 300px !important;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="text-green-600 font-medium">{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('g:i A') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Salida -->
                        <div class="flex items-center">
                            @if($todayAttendance && !$todayAttendance->check_out_time)
                                <form action="{{ route('attendance.check-out') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center space-x-2 bg-white hover:bg-indigo-50 rounded-lg p-2">
                                        <div class="bg-indigo-100 rounded-full p-2">
                                            <svg class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 300px !important;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        <span class="text-indigo-600 font-medium">Salida</span>
                                    </button>
                                </form>
                            @elseif($todayAttendance && $todayAttendance->check_out_time)
                                <div class="flex items-center space-x-2">
                                    <div class="bg-indigo-100 rounded-full p-2">
                                        <svg class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 300px !important;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="text-indigo-600 font-medium">{{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('g:i A') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Historial -->
                        <a href="{{ route('attendance.list') }}" class="flex items-center space-x-2 text-blue-600 hover:text-blue-700">
                            <span class="font-medium">Historial</span>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 300px !important;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div class="mt-4 text-center text-sm text-gray-600">
                        <span class="font-medium">Tip:</span> Recuerda marcar tu entrada y salida diariamente
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection