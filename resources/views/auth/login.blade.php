<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SncPharma Asistencia</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="min-h-screen bg-custom flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-gray-900/95 backdrop-blur-lg rounded-2xl p-8 shadow-2xl border border-gray-800" style="
    background: #1F4591;
">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <div class="flex justify-center">
                    <img class="w-16" src="/images/logo/logo-white-250.png" alt="SncPharma Logo">
                </div>
                <h2 class="mt-6 text-2xl font-bold text-white">
                    Bienvenido de nuevo
                </h2>
                <p class="mt-2 text-sm text-gray-400">
                    Ingresa tus credenciales para continuar
                </p>
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">
                        Correo Electrónico
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" required
                            class="w-full px-4 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('email') }}"
                            placeholder="ejemplo@correo.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300">
                        Contraseña
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                            class="w-full px-4 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recordarme y Olvidé contraseña -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-gray-700 bg-gray-800/50 text-blue-500 focus:ring-blue-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-300">
                            Recordarme
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" 
                               class="text-blue-400 hover:text-blue-300 transition-colors">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Botón de Login -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <!-- Enlace para Registrarse -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-400">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" 
                       class="font-medium text-blue-400 hover:text-blue-300 transition-colors">
                        Regístrate aquí
                    </a>
                </p>
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
    </style>
</body>
</html>