@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#1a4175] to-[#2563eb]  rounded-lg shadow-lg p-8 mb-8 transform hover:scale-[1.02] transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-3" style="color: black">¡Bienvenido al Panel de Administración!</h1>
                <p class="text-xl opacity-90">
                    Desde aquí puedes gestionar los empleados y supervisar la asistencia del personal.
                </p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Empleados Activos -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-[#1a4175] transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-[#1a4175] bg-opacity-10">
                    <svg class="h-8 w-8 text-[#1a4175]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-gray-800">Empleados Activos</h3>
                    <p class="text-3xl font-bold text-[#1a4175]">{{ $users->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Registros Hoy -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500 transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-emerald-100">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-gray-800">Registros Hoy</h3>
                    <p class="text-3xl font-bold text-emerald-600">0</p>
                </div>
            </div>
        </div>

        <!-- Personal Presente -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-gray-800">Personal Presente</h3>
                    <p class="text-3xl font-bold text-blue-600">0</p>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-xl shadow-md p-6 transform hover:scale-105 transition-transform duration-300">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.users.create') }}" 
                   class="flex items-center justify-center w-full bg-gradient-to-r from-[#1a4175] to-[#2563eb] px-4 py-2 rounded-lg hover:from-[#15345d] hover:to-[#1e40af] transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nuevo Usuario
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center justify-center w-full border-2 border-[#1a4175] text-[#1a4175] px-4 py-2 rounded-lg hover:bg-[#1a4175]  transition-colors duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Ver Usuarios
                </a>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Usuarios Recientes</h3>
            <a href="{{ route('admin.users.index') }}" class="text-[#1a4175] hover:text-[#15345d] transition-colors duration-300">
                Ver todos →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gradient-to-r from-[#1a4175] to-[#2563eb] uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Nombre</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Identificación</th>
                        <th class="py-3 px-6 text-left">Fecha de Ingreso</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @foreach($users as $user)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-3 px-6 text-left">{{ $user->name }}</td>
                            <td class="py-3 px-6 text-left">{{ $user->email }}</td>
                            <td class="py-3 px-6 text-left">{{ $user->identification }}</td>
                            <td class="py-3 px-6 text-left">{{ $user->join_date ? $user->join_date->format('d/m/Y') : 'No especificada' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection