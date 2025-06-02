@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="  rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Control de Asistencia</h2>
            <div class="flex items-center gap-4">
                <form action="{{ route('admin.attendance.export-range') }}" method="GET" class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600">Desde:</label>
                        <input type="date" name="start_date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600">Hasta:</label>
                        <input type="date" name="end_date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center gap-2" style="background-color: #1a4175">
                        <i class="fas fa-file-excel"></i>
                        Exportar Excel
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full   rounded-lg overflow-hidden" style="width: 100%;">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4   text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4   text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4   text-sm text-gray-500">
                            {{ $user->location }}
                        </td>
                        <td class="px-6 py-4   text-sm font-medium">
                            <a href="{{ route('admin.attendance.user', $user->id) }}" 
                               class="bg-[#1a4175]  px-4 py-2 rounded-md hover:bg-[#15345d] inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="
                                                width: 68px;
                                            ">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Gestionar Asistencia
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
