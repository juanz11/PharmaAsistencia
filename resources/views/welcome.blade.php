<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SncPharma Asistencia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        .feature-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.1) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.47);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .feature-title {
            background: linear-gradient(90deg, #ffffff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 600;
        }
        .feature-text {
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.6;
            font-size: 0.95rem;
        }
    </style>
</head>
<body class="bg-custom min-h-screen">
    <div class="glass-effect min-h-screen flex flex-col items-center justify-center py-12">
        <div class="max-w-6xl w-full px-6">
            <!-- Logo y Título -->
            <div class="text-center mb-16">
                <img src="https://sncpharma.com/wp-content/uploads/2024/09/logo-white-250.svg" alt="SncPharma Logo" class="mx-auto mb-8 max-w-xs">
                <h1 class="text-5xl font-bold text-white mb-4 tracking-tight">SncPharma Asistencia</h1>
                <p class="text-xl text-gray-300 tracking-wide">Sistema Integral de Control de Asistencia</p>
            </div>
            
            <!-- Características Principales -->
            <div class="grid md:grid-cols-3 gap-8 mt-12">
                <div class="feature-card rounded-xl p-8">
                    <h2 class="feature-title text-2xl mb-4">Control de Asistencia</h2>
                    <p class="feature-text">Registra y gestiona la asistencia del personal de manera eficiente y segura, con un sistema moderno y fácil de usar.</p>
                </div>
                
                <div class="feature-card rounded-xl p-8">
                    <h2 class="feature-title text-2xl mb-4">Reportes Detallados</h2>
                    <p class="feature-text">Genera informes completos y personalizados de asistencia, con estadísticas avanzadas y exportación de datos.</p>
                </div>

                <div class="feature-card rounded-xl p-8">
                    <h2 class="feature-title text-2xl mb-4">Gestión de Personal</h2>
                    <p class="feature-text">Administra la información general y horarios de forma centralizada, con control total y seguridad.</p>
                </div>
            </div>

            <!-- Botón de Acceso -->
            <div class="mt-16 text-center">
                <a href="/login" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-12 rounded-full text-lg transition duration-300 inline-flex items-center transform hover:scale-105 shadow-lg">
                    Iniciar Sesión
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            <!-- Footer -->
            <footer class="mt-16 text-center">
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} SncPharma. Todos los derechos reservados.</p>
            </footer>
        </div>
    </div>
</body>
</html>
