@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if(auth()->user()->role === 'admin')
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body p-4">
                        <!-- Contenedor principal con espaciado responsive -->
                        <div class="space-y-4 md:space-y-6">
                            <!-- Encabezado con efecto de gradiente -->
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-4 md:p-6 relative overflow-hidden" style="
    display: inline-block;
">
                                <div class="absolute inset-0 bg-grid-white/[0.05]"></div>
                                <div class="relative flex flex-col md:flex-row items-center justify-between gap-4">
                                    <div class="text-center md:text-left">
                                        <h2 class="text-xl md:text-2xl font-bold  mb-2">Panel de Administración</h2>
                                        <p class="text-blue-100 text-sm md:text-base">Gestión y control de asistencia del personal</p>
                                    </div>
                                    <img src="https://sncpharma.com/wp-content/uploads/2024/11/lightbulb-1.png" alt="Logo" class="h-10 md:h-12 w-auto">
                                </div>
                            </div>

                            <!-- Sección de Gestión -->
                            

                            <!-- Cards de navegación -->
                            <div class="flex flex-col md:flex-row justify-center gap-4 md:gap-6" style="
    display: inline-block;
">
                                <!-- Card de Empleados -->
                                <a href="/admin/users" class="transform transition-all duration-200 hover:scale-[1.02] flex-1" style="
    width: 286px!important;!importan;!importa;!import;!impor;!impo;!imp;!im;!i;!;
">
                                    <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm hover:shadow-md">
                                        <div class="flex flex-col items-center space-y-3" style="
    width: 229px;
">
                                            <div class="bg-blue-100 rounded-lg p-4 w-full flex justify-center" style="
    width: 200px;
">
                                                <svg class="w-[300px] h-[300px] text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="
    width: 200px;
">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-center">
                                                <h3 class="text-lg font-semibold text-gray-900">Empleados</h3>
                                                <p class="text-sm md:text-base text-gray-600">Gestionar personal</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card de Asistencias -->
                                <a href="/admin/reports" class="transform transition-all duration-200 hover:scale-[1.02] flex-1" style="
    width: 286px;
">
                                    <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm hover:shadow-md" style="
    width: 286px;
">
                                        <div class="flex flex-col items-center space-y-3" style="
    width: 200px;
">
                                            <div class="bg-indigo-100 rounded-lg p-4 w-full flex justify-center" style="
    width: 200px;
">
                                                <svg class="w-[300px] h-[300px] text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="
    width: 200px;
">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-center">
                                                <h3 class="text-lg font-semibold text-gray-900">Asistencias</h3>
                                                <p class="text-sm md:text-base text-gray-600">Control de marcaciones</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <!-- Contenedor principal con flexbox -->
                        <div class="d-flex flex-column flex-md-row align-items-center" style="
    width: 80%;
">
                            <!-- Columna de la imagen - ajusta automáticamente su tamaño -->
                            <div class="col-12 col-md-6 p-4">
                                <img src="https://muestras.sncpharma.com/images/logo/logo.png" alt="Logo" class="img-fluid rounded-3 shadow-sm" style="max-width: 100%; height: auto;">
                            </div>

                            <!-- Columna del contenido - se ajusta al espacio restante -->
                            <div class="col-12 col-md-6 p-4">
                                <div class="text-center mb-4">
                                    <h2 class="mb-1">{{ auth()->user()->name }}</h2>
                                    <p class="text-muted mb-4">{{ now()->format('d/m/Y') }}</p>

                                    <div id="clock" class="display-4 mb-4">00:00:00</div>

                                    <div class="d-flex justify-content-center gap-3 mb-4">
                                        <button onclick="markAttendance('check-in')" class="btn btn-primary px-4" id="checkInBtn">
                                            <i class="fas fa-sign-in-alt me-2"></i>Entrada
                                        </button>
                                        <button onclick="markAttendance('break-start')" class="btn btn-info px-4" id="breakStartBtn">
                                            <i class="fas fa-coffee me-2"></i>Almuerzo
                                        </button>
                                        <button onclick="markAttendance('break-end')" class="btn btn-warning px-4" id="breakEndBtn">
                                            <i class="fas fa-utensils me-2"></i>Fin Almuerzo
                                        </button>
                                        <button onclick="markAttendance('check-out')" class="btn btn-danger px-4" id="checkOutBtn">
                                            <i class="fas fa-sign-out-alt me-2"></i>Salida
                                        </button>
                                    </div>

                                    <!-- Estado de Asistencia -->
                                    <div class="attendance-status">
                                        <div class="d-flex justify-content-center gap-4">
                                            <div class="text-center">
                                                <p class="text-muted mb-1">Entrada</p>
                                                <p class="h5" id="checkInTime">
                                                    @if(isset($todayAttendance) && $todayAttendance->check_in)
                                                        {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('h:i A') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-muted mb-1">Almuerzo</p>
                                                <p class="h5" id="breakStartTime">
                                                    @if(isset($todayAttendance) && $todayAttendance->break_start)
                                                        {{ \Carbon\Carbon::parse($todayAttendance->break_start)->format('h:i A') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-muted mb-1">Fin Almuerzo</p>
                                                <p class="h5" id="breakEndTime">
                                                    @if(isset($todayAttendance) && $todayAttendance->break_end)
                                                        {{ \Carbon\Carbon::parse($todayAttendance->break_end)->format('h:i A') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-muted mb-1">Salida</p>
                                                <p class="h5" id="checkOutTime">
                                                    @if(isset($todayAttendance) && $todayAttendance->check_out)
                                                        {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('h:i A') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mensajes informativos - se ajustan al ancho completo debajo del contenido principal -->
                        <div class="p-4 bg-light rounded-bottom">
                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-10">
                                    <div class="card shadow-sm">
                                        <div class="card-body text-center">
                                            <p class="text-gray-600 mb-3">
                                                <i class="fas fa-info-circle text-primary me-2"></i>
                                                Este sistema de asistencia es una herramienta diseñada para apoyar la organización del equipo y facilitar nuestra dinámica de trabajo. 
                                                Su objetivo es dar cumplimiento a las políticas de la empresa y garantizar el compromiso y sentido de responsabilidad de los trabajadores respecto a su horario de trabajo.
                                            </p>
                                            <p class="text-gray-600 mb-3">
                                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                                De acuerdo al Art. 167 de la LOTTT se entiende por jornada de trabajo, el tiempo durante el cual el trabajador o la trabajadora está a
                                                    disposición para cumplir con las responsabilidades y tareas a su cargo.
                                            </p>
                                            <p class="text-gray-600 mb-0">
                                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                                <strong>Recuerda marcar tu entrada y salida cada día.</strong> 
                                                ¡Tu participación es clave para que todo funcione mejor!
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const userDepartment = "{{ auth()->user()->department }}";

function isMobileDevice() {
    // Detecta si es realmente un dispositivo móvil y no solo una vista móvil
    const isRealMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) 
        && ('ontouchstart' in window) 
        && !window.matchMedia('(pointer: fine)').matches 
        && !window.matchMedia('(hover: hover)').matches;

    // Detecta si es una vista de dispositivo en las herramientas de desarrollo
    const isDevTools = window.outerWidth !== window.innerWidth 
        && /Mobile|Android|iPhone/i.test(navigator.userAgent);

    // Solo retorna true si es un dispositivo móvil real y no las herramientas de desarrollo
    return isRealMobile && !isDevTools;
}

function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
}

setInterval(updateClock, 1000);
updateClock();

function markAttendance(type) {
    if (isMobileDevice() && userDepartment !== 'COMERCIAL') {
        Swal.fire({
            icon: 'error',
            title: 'Acceso Denegado',
            text: 'No puedes marcar fuera de las instalaciones. Por favor, utiliza una computadora registrada.',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    fetch(`/attendance/${type}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'Aceptar'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No se pudo procesar la solicitud',
            confirmButtonText: 'Aceptar'
        });
    });
}

function updateAttendanceDisplay(attendance) {
    // Función para formatear la hora
    function formatTime(time) {
        if (!time) return '--:--';
        const date = new Date(time);
        return date.toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    // Actualizar cada campo con el formato correcto
    document.getElementById('checkInTime').textContent = formatTime(attendance.check_in);
    document.getElementById('breakStartTime').textContent = formatTime(attendance.break_start);
    document.getElementById('breakEndTime').textContent = formatTime(attendance.break_end);
    document.getElementById('checkOutTime').textContent = formatTime(attendance.check_out);
}

function showSuccessMessage(message) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: message,
        timer: 2000,
        showConfirmButton: false
    });
}

function showErrorMessage(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message
    });
}

// Cargar estado inicial de asistencia
document.addEventListener('DOMContentLoaded', function() {
    fetch('/attendance/status')
        .then(response => response.json())
        .then(data => {
            if (data.attendance) {
                updateAttendanceDisplay(data.attendance);
            }
        })
        .catch(error => console.error('Error:', error));
});
</script>
@endpush