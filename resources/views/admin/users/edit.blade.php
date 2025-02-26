@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-4">
    <div class="max-w-lg mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-[#1a4175] px-4 py-2">
                <h2 class="text-lg font-medium text-white">Editar Empleado</h2>
            </div>
            
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-4">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre
                        </label>
                        <input type="text" name="name" id="name" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1a4175] focus:border-[#1a4175]"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cédula -->
                    <div>
                        <label for="identification" class="block text-sm font-medium text-gray-700 mb-1">
                            Cédula
                        </label>
                        <input type="text" name="identification" id="identification" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1a4175] focus:border-[#1a4175]"
                            value="{{ old('identification', $user->identification) }}">
                        @error('identification')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Ingreso -->
                    <div>
                        <label for="join_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha de Ingreso
                        </label>
                        <input type="date" name="join_date" id="join_date" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1a4175] focus:border-[#1a4175]"
                            value="{{ old('join_date', $user->join_date?->format('Y-m-d')) }}">
                        @error('join_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Correo Electrónico
                        </label>
                        <input type="email" name="email" id="email" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1a4175] focus:border-[#1a4175]"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ubicación -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                            Ubicación
                        </label>
                        <input type="text" name="location" id="location" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1a4175] focus:border-[#1a4175]"
                            value="{{ old('location', $user->location) }}">
                        @error('location')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-800">
                        Cancelar
                    </a>
                    <button type="submit" 
                        class="px-4 py-2 bg-[#1a4175] text-white text-sm font-medium rounded-md hover:bg-[#15345d] focus:outline-none focus:ring-2 focus:ring-[#1a4175] focus:ring-offset-2">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
