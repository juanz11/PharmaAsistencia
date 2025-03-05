@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="  rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Control de Asistencia - {{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->email }} - {{ $user->location }}</p>
            </div>
            <a href="{{ route('admin.attendance.index') }}" class="bg-gray-500  px-4 py-2 rounded-md hover:bg-gray-600">
                Volver
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full   rounded-lg overflow-hidden" style="width: 100%;">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrada</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salida</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($attendances as $attendance)
                    <tr>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            {{ $attendance->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            <input type="datetime-local" 
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ $attendance->check_in ? date('Y-m-d\TH:i', strtotime($attendance->check_in)) : '' }}"
                                   form="update-form-{{ $attendance->id }}"
                                   name="check_in">
                        </td>
                        <td class="px-6 py-4  text-sm text-gray-500">
                            <input type="datetime-local" 
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ $attendance->check_out ? date('Y-m-d\TH:i', strtotime($attendance->check_out)) : '' }}"
                                   form="update-form-{{ $attendance->id }}"
                                   name="check_out">
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <form id="update-form-{{ $attendance->id }}" 
                                  action="{{ route('admin.attendance.update', $attendance->id) }}" 
                                  method="POST" 
                                  class="inline-block">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-[#1a4175]  px-3 py-1 rounded-md hover:bg-[#15345d] mr-2">
                                    Actualizar
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.attendance.destroy', $attendance->id) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('¿Está seguro de que desea eliminar esta asistencia?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600  px-3 py-1 rounded-md hover:bg-red-700">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
