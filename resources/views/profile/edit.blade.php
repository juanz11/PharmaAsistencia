@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-[#1a4175] px-6 py-4">
                <h2 class="text-xl font-semibold ">Mi Perfil</h2>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nombre Completo
                    </label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1a4175] focus:ring focus:ring-[#1a4175] focus:ring-opacity-50"
                        value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cédula -->
                <div>
                    <label for="identification" class="block text-sm font-medium text-gray-700">
                        Cédula de Identidad
                    </label>
                    <input type="text" name="identification" id="identification"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1a4175] focus:ring focus:ring-[#1a4175] focus:ring-opacity-50"
                        value="{{ old('identification', auth()->user()->identification) }}" required>
                    @error('identification')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Correo Electrónico
                    </label>
                    <input type="email" name="email" id="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1a4175] focus:ring focus:ring-[#1a4175] focus:ring-opacity-50"
                        value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ubicación -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">
                        Ubicación
                    </label>
                    <input type="text" name="location" id="location"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1a4175] focus:ring focus:ring-[#1a4175] focus:ring-opacity-50"
                        value="{{ old('location', auth()->user()->location) }}" required>
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Cambiar Contraseña</h3>
                    
                    <!-- Contraseña Actual -->
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">
                                Contraseña Actual
                            </label>
                            <input type="password" name="current_password" id="current_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1a4175] focus:ring focus:ring-[#1a4175] focus:ring-opacity-50">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nueva Contraseña -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700">
                                Nueva Contraseña
                            </label>
                            <input type="password" name="new_password" id="new_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1a4175] focus:ring focus:ring-[#1a4175] focus:ring-opacity-50">
                            @error('new_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmar Nueva Contraseña -->
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">
                                Confirmar Nueva Contraseña
                            </label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1a4175] focus:ring focus:ring-[#1a4175] focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit"
                        class="px-4 py-2 bg-[#1a4175] rounded-md hover:bg-[#15345d] focus:outline-none focus:ring-2 focus:ring-[#1a4175] focus:ring-offset-2">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
