@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Encabezado con gradiente más llamativo -->
            <div class="bg-gradient-to-r from-[#1a4175] to-[#1F4591] rounded-t-lg shadow-lg p-6 mb-1">
                <h2 class="text-2xl font-bold  mb-2">{{ __('Mi Perfil') }}</h2>
                <p class="text-blue-100">Actualiza tu información personal</p>
            </div>

            <!-- Contenido Principal con mejor contraste -->
            <div class="bg-white rounded-b-lg shadow-lg">
                @if (session('status'))
                    <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('user.update') }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Sección de Información Personal -->
                    <div class="bg-blue-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-[#1a4175] mb-4 border-b border-blue-200 pb-2">
                            Información Personal
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nombre') }}</label>
                                <input id="name" type="text" 
                                    class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                                    name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Correo Electrónico') }}</label>
                                <input id="email" type="email" 
                                    class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror" 
                                    name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Contacto -->
                    <div class="bg-indigo-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-indigo-900 mb-4 border-b border-indigo-200 pb-2">
                            Información de Contacto
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Teléfono -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Teléfono') }}</label>
                                <input id="phone" type="text" 
                                    class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    name="phone" value="{{ old('phone', $user->phone) }}">
                            </div>

                            <!-- Dirección -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Dirección') }}</label>
                                <input id="address" type="text" 
                                    class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    name="address" value="{{ old('address', $user->address) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Contacto de Emergencia -->
                    <div class="bg-purple-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4 border-b border-purple-200 pb-2">
                            Contacto de Emergencia
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre del Contacto -->
                            <div>
                                <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nombre del Contacto') }}</label>
                                <input id="emergency_contact" type="text" 
                                    class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}">
                            </div>

                            <!-- Teléfono de Emergencia -->
                            <div>
                                <label for="emergency_phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Teléfono de Emergencia') }}</label>
                                <input id="emergency_phone" type="text" 
                                    class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    name="emergency_phone" value="{{ old('emergency_phone', $user->emergency_phone) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Botón de Actualizar -->
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-[#1a4175] to-[#1F4591] font-semibold rounded-lg shadow-md hover:from-[#163670] hover:to-[#1a4175] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1F4591] transition-all duration-200 transform hover:scale-[1.02] flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            {{ __('Actualizar Información') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
