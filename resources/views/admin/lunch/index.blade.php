@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="m-0 text-primary">Control de Almuerzos</h3>
                    <div class="d-flex gap-3">
                        <input type="date" id="startDate" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}">
                        <input type="date" id="endDate" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}">
                        <button class="btn btn-primary btn-sm" onclick="filterData()">Filtrar</button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Estadísticas -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-2">Promedio de Duración</h6>
                                    <h3 class="text-primary mb-0" id="averageDuration">--:--</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-2">Almuerzos Completados</h6>
                                    <h3 class="text-success mb-0" id="completedCount">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-2">Tiempo Excedido</h6>
                                    <h3 class="text-danger mb-0" id="exceededCount">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-2">Tiempo Corto</h6>
                                    <h3 class="text-warning mb-0" id="shortCount">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cuadro de Tiempos -->
                    <div class="bg-light p-3 rounded mb-4">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white shadow-sm">
                                    <h5 class="text-success">Tiempo Ideal</h5>
                                    <p class="display-6">30-60 min</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white shadow-sm">
                                    <h5 class="text-warning">Tiempo Corto</h5>
                                    <p class="display-6">&lt; 30 min</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 bg-white shadow-sm">
                                    <h5 class="text-danger">Tiempo Excedido</h5>
                                    <p class="display-6">&gt; 60 min</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Almuerzos -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Empleado</th>
                                    <th>Fecha</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Duración</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="lunchTableBody">
                                @foreach($lunchData as $lunch)
                                <tr>
                                    <td>{{ $lunch['user'] }}</td>
                                    <td>{{ $lunch['date'] }}</td>
                                    <td>{{ $lunch['start'] }}</td>
                                    <td>{{ $lunch['end'] }}</td>
                                    <td>{{ $lunch['duration'] }}</td>
                                    <td>
                                        <span class="{{ $lunch['statusClass'] }}">
                                            {{ $lunch['status'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
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
<script>
function filterData() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    fetch(`/admin/lunch/data?start=${startDate}&end=${endDate}`)
        .then(response => response.json())
        .then(data => {
            updateTable(data.records);
            updateStats(data.stats);
        })
        .catch(error => console.error('Error:', error));
}

function updateTable(records) {
    const tbody = document.getElementById('lunchTableBody');
    tbody.innerHTML = '';

    records.forEach(lunch => {
        tbody.innerHTML += `
            <tr>
                <td>${lunch.user}</td>
                <td>${lunch.date}</td>
                <td>${lunch.start}</td>
                <td>${lunch.end}</td>
                <td>${lunch.duration}</td>
                <td><span class="${lunch.statusClass}">${lunch.status}</span></td>
            </tr>
        `;
    });
}

function updateStats(stats) {
    document.getElementById('averageDuration').textContent = stats.averageDuration;
    document.getElementById('completedCount').textContent = stats.completedCount;
    document.getElementById('exceededCount').textContent = stats.exceededCount;
    document.getElementById('shortCount').textContent = stats.shortCount;
}

// Actualizar la página cada minuto para mantener las duraciones actualizadas
setInterval(function() {
    filterData();
}, 60000);

// Cargar datos iniciales
document.addEventListener('DOMContentLoaded', filterData);
</script>
@endpush
