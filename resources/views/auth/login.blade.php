@extends('layouts.app')

@section('content')
<div class="bg-custom min-h-screen">
    <div class="glass-effect min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo y Título -->
            <div class="text-center">
                <img src="/images/logo/logo-white-250.png" alt="SncPharma Logo" class="mx-auto h-20 w-auto mb-6">
                <h2 class="text-3xl font-bold text-white">
                    Iniciar Sesión
                </h2>
                <p class="mt-2 text-gray-300">
                    Ingrese sus credenciales para acceder
                </p>
            </div>

            <!-- Formulario -->
            <div class="form-card mt-8 p-8 rounded-xl">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">
                            Correo Electrónico
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" required
                                class="form-input appearance-none rounded-lg block w-full px-3 py-2 placeholder-gray-400"
                                value="{{ old('email') }}"
                                placeholder="ejemplo@correo.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300">
                            Contraseña
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required
                                class="form-input appearance-none rounded-lg block w-full px-3 py-2 placeholder-gray-400"
                                placeholder="Ingrese su contraseña">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recordarme -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-300">
                                Recordarme
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-blue-400 hover:text-blue-300">
                                    ¿Olvidó su contraseña?
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Botón de Login -->
                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition duration-300">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>

                <!-- Enlace para Registrarse -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-300">
                        ¿No tiene una cuenta?
                        <a href="{{ route('register') }}" class="font-medium text-blue-400 hover:text-blue-300">
                            Registrarse
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-custom {
        background-image: url('/images/background/login-bg.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    .glass-effect {
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
    }
    .form-card {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.1) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
    .form-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    .form-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    .form-input:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
</style>
@endsection
