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

                    <div class="card-body">
                        {{ __('¡Bienvenido Administrador!') }}
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <!-- Contenedor principal con flexbox -->
                        <div class="d-flex flex-column flex-md-row align-items-center">
                            <!-- Columna de la imagen - ajusta automáticamente su tamaño -->
                            <div class="col-12 col-md-6 p-4">
                                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="img-fluid rounded-3 shadow-sm" style="max-width: 100%; height: auto;">
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
                                                <span class="font-medium"><strong>Nota:</strong></span> 
                                                Este sistema de asistencia es una herramienta diseñada para apoyar la organización del equipo y facilitar nuestra dinámica de trabajo. 
                                                Su objetivo es ayudarnos a mantener una comunicación fluida y asegurar que todos estemos alineados. 😊
                                            </p>
                                            <p class="text-gray-600 mb-0">
                                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                                <span class="font-medium"><strong>Tip:</strong></span> 
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
    const button = document.querySelector(`button[onclick="markAttendance('${type}')"]`);
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/attendance/${type}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateAttendanceDisplay(data.attendance);
            showSuccessMessage(data.message);
        } else {
            showErrorMessage(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error al marcar la asistencia');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
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