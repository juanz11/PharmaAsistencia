<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - SncPharma Asistencia</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-custom {
            background-image: url('https://muestras.sncpharma.com//images/background/login-bg.jpg');
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
</head>
<body class="bg-custom min-h-screen">
    <div class="glass-effect min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo y Título -->
            <div class="text-center">
                <img src="https://sncpharma.com/wp-content/uploads/2024/09/logo-white-250.svg" alt="SncPharma Logo" class="mx-auto h-20 w-auto mb-6">
                <h2 class="text-3xl font-bold text-white">
                    Registro de Usuario
                </h2>
                <p class="mt-2 text-gray-300">
                    Complete el formulario para crear su cuenta
                </p>
            </div>

            <div class="form-card mt-8 p-8 rounded-xl">
                <form class="space-y-6" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nombre Completo -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-white">
                            Nombre Completo
                        </label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" required
                                class="form-input w-full px-3 py-2 rounded-md @error('name') border-red-500 @enderror"
                                placeholder="Ingrese su nombre completo"
                                value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Cédula -->
                    <div>
                        <label for="identification" class="block text-sm font-medium text-white">
                            Cédula de Identidad
                        </label>
                        <div class="mt-1">
                            <input id="identification" name="identification" type="text" required
                                class="form-input w-full px-3 py-2 rounded-md @error('identification') border-red-500 @enderror"
                                placeholder="Ingrese su número de cédula"
                                value="{{ old('identification') }}">
                            @error('identification')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Correo Electrónico -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white">
                            Correo Electrónico
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" required
                                class="form-input w-full px-3 py-2 rounded-md @error('email') border-red-500 @enderror"
                                placeholder="ejemplo@correo.com"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Ubicación -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-white">
                            Ubicación
                        </label>
                        <div class="mt-1">
                            <input id="location" name="location" type="text" required
                                class="form-input w-full px-3 py-2 rounded-md @error('location') border-red-500 @enderror"
                                placeholder="Ciudad, Estado"
                                value="{{ old('location') }}">
                            @error('location')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Fecha de Ingreso -->
                    <div>
                        <label for="join_date" class="block text-sm font-medium text-white">
                            Fecha de Ingreso
                        </label>
                        <div class="mt-1">
                            <input id="join_date" name="join_date" type="date" required
                                class="form-input w-full px-3 py-2 rounded-md @error('join_date') border-red-500 @enderror"
                                value="{{ old('join_date') }}">
                            @error('join_date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Departamento -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-white">
                            Departamento
                        </label>
                        <div class="mt-1">
                            <select id="department" name="department" required
                                class="form-input w-full px-3 py-2 rounded-md text-gray-900 @error('department') border-red-500 @enderror" style="
    color: black;
">
                                <option value="">Seleccionar departamento</option>
                                <option value="RECURSOS HUMANOS" {{ old('department') == 'RECURSOS HUMANOS' ? 'selected' : '' }}>RECURSOS HUMANOS</option>
                                <option value="PRESIDENCIA" {{ old('department') == 'PRESIDENCIA' ? 'selected' : '' }}>PRESIDENCIA</option>
                                <option value="ADMINISTRACIÓN" {{ old('department') == 'ADMINISTRACIÓN' ? 'selected' : '' }}>ADMINISTRACIÓN</option>
                                <option value="COMERCIAL" {{ old('department') == 'COMERCIAL' ? 'selected' : '' }}>COMERCIAL</option>
                                <option value="MERCADEO" {{ old('department') == 'MERCADEO' ? 'selected' : '' }}>MERCADEO</option>
                                <option value="CONSULTORÍA JURÍDICA" {{ old('department') == 'CONSULTORÍA JURÍDICA' ? 'selected' : '' }}>CONSULTORÍA JURÍDICA</option>
                                <option value="LOGÍSTICA Y ALMACÉN" {{ old('department') == 'LOGÍSTICA Y ALMACÉN' ? 'selected' : '' }}>LOGÍSTICA Y ALMACÉN</option>
                                <option value="SERVICIOS GENERALES" {{ old('department') == 'SERVICIOS GENERALES' ? 'selected' : '' }}>SERVICIOS GENERALES</option>
                                <option value="MENTE Y SALUD" {{ old('department') == 'MENTE Y SALUD' ? 'selected' : '' }}>MENTE Y SALUD</option>
                                <option value="TECNOLOGÍA DE LA INFORMACIÓN" {{ old('department') == 'TECNOLOGÍA DE LA INFORMACIÓN' ? 'selected' : '' }}>TECNOLOGÍA DE LA INFORMACIÓN</option>
                                <option value="FINANZAS" {{ old('department') == 'FINANZAS' ? 'selected' : '' }}>FINANZAS</option>
                            </select>
                            @error('department')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white">
                            Contraseña
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required
                                class="form-input w-full px-3 py-2 rounded-md @error('password') border-red-500 @enderror"
                                placeholder="••••••••">
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-white">
                            Confirmar Contraseña
                        </label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="form-input w-full px-3 py-2 rounded-md"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1a4175] hover:bg-[#15345d] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1a4175]">
                            Crear Cuenta
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-300">
                        ¿Ya tiene una cuenta?
                        <a href="{{ route('login') }}" class="font-medium text-blue-400 hover:text-blue-300">
                            Iniciar Sesión
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
