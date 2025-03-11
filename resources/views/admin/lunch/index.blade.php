@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="m-0 text-primary">Control de Almuerzos</h3>
                    <div class="text-muted">{{ now()->format('d/m/Y') }}</div>
                </div>

                <div class="card-body">
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
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Duración</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lunchData as $lunch)
                                <tr>
                                    <td>{{ $lunch['user'] }}</td>
                                    <td>{{ $lunch['start'] }}</td>
                                    <td>{{ $lunch['end'] }}</td>
                                    <td>{{ $lunch['duration'] }}</td>
                                    <td>
                                        <span class="{{ $lunch['statusClass'] }}">
                                            {{ $lunch['status'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editLunch({{ $lunch['id'] }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
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

<!-- Modal de Edición -->
<div class="modal fade" id="editLunchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Tiempo de Almuerzo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editLunchForm">
                    <input type="hidden" id="lunchId">
                    <div class="mb-3">
                        <label for="breakStart" class="form-label">Hora de Inicio</label>
                        <input type="time" class="form-control" id="breakStart" name="break_start">
                    </div>
                    <div class="mb-3">
                        <label for="breakEnd" class="form-label">Hora de Fin</label>
                        <input type="time" class="form-control" id="breakEnd" name="break_end">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveLunchTime()">Guardar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function editLunch(id) {
    $('#lunchId').val(id);
    $('#editLunchModal').modal('show');
}

function saveLunchTime() {
    const id = $('#lunchId').val();
    const breakStart = $('#breakStart').val();
    const breakEnd = $('#breakEnd').val();

    $.ajax({
        url: `/admin/lunch/${id}`,
        method: 'PUT',
        data: {
            break_start: breakStart,
            break_end: breakEnd,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $('#editLunchModal').modal('hide');
                location.reload();
            }
        },
        error: function(xhr) {
            alert('Error al guardar los cambios');
        }
    });
}

// Actualizar la página cada minuto para mantener las duraciones actualizadas
setInterval(function() {
    location.reload();
}, 60000);
</script>
@endpush
