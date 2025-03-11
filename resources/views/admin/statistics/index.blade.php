@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Estadísticas de Asistencia</h1>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rankings de Asistencia</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year" class="form-label">Año:</label>
                                <select id="year" class="form-select">
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = 2023;
                                    @endphp
                                    @for($year = $startYear; $year <= $currentYear + 2; $year++)
                                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="month" class="form-label">Mes:</label>
                                <select id="month" class="form-select">
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="day" class="form-label">Día:</label>
                                <select id="day" class="form-select">
                                    <option value="all">Todos los días</option>
                                    @for($day = 1; $day <= 31; $day++)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type" class="form-label">Tipo:</label>
                                <select id="type" class="form-select">
                                    <option value="entrada">Entrada</option>
                                    <option value="salida">Salida</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Posición</th>
                                    <th>Nombre</th>
                                    <th>Mejor Tiempo</th>
                                    <th>Dispositivo</th>
                                    <th>Días a Tiempo</th>
                                    <th>Total de Días</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody id="rankings-body">
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando datos...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Establecer mes actual
    const currentMonth = new Date().getMonth() + 1;
    document.getElementById('month').value = currentMonth;

    // Cargar rankings iniciales
    loadRankings();

    // Agregar event listeners
    ['year', 'month', 'day', 'type'].forEach(id => {
        document.getElementById(id).addEventListener('change', loadRankings);
    });
});

function loadRankings() {
    const year = document.getElementById('year').value;
    const month = document.getElementById('month').value;
    const day = document.getElementById('day').value;
    const type = document.getElementById('type').value;

    // Mostrar mensaje de carga
    document.getElementById('rankings-body').innerHTML = `
        <tr>
            <td colspan="7" class="text-center">
                <i class="fas fa-spinner fa-spin"></i> Cargando...
            </td>
        </tr>
    `;

    // Calcular fechas de inicio y fin
    let startDate, endDate;
    if (day === 'all') {
        startDate = new Date(year, month - 1, 1);
        endDate = new Date(year, month, 0); // Último día del mes
    } else {
        startDate = new Date(year, month - 1, day);
        endDate = new Date(year, month - 1, day, 23, 59, 59);
    }

    // Formatear fechas para la API
    const formatDate = (date) => {
        return date.toISOString().split('T')[0];
    };

    fetch(`/admin/statistics/rankings?start_date=${formatDate(startDate)}&end_date=${formatDate(endDate)}&type=${type}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.getElementById('rankings-body');
            tbody.innerHTML = '';

            if (data.error) {
                throw new Error(data.error);
            }

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="fas fa-info-circle"></i> No hay datos disponibles para el período seleccionado
                        </td>
                    </tr>`;
                return;
            }

            data.forEach((item, index) => {
                const percentage = ((item.on_time_days / item.total_days) * 100).toFixed(1);
                const deviceClass = item.is_unusual_device ? 'text-danger' : '';
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.name}</td>
                        <td>${item.best_time}</td>
                        <td class="${deviceClass}">${item.device}</td>
                        <td>${item.on_time_days}</td>
                        <td>${item.total_days}</td>
                        <td>${percentage}%</td>
                    </tr>`;
                tbody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('rankings-body').innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger">
                        <i class="fas fa-exclamation-circle"></i> Error al cargar los datos: ${error.message}
                    </td>
                </tr>`;
        });
}
</script>
@endpush
