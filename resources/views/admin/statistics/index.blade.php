@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Ranking de Asistencia</h2>
        
        <!-- Selectores de fecha -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mes</label>
                <select id="monthSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Año</label>
                <select id="yearSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @for($i = date('Y'); $i >= 2024; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por</label>
                <div class="flex gap-2">
                    <button onclick="loadRankings('entrada')" class="flex-1 bg-blue-100 text-blue-600 hover:bg-blue-200 px-4 py-2 rounded-md">
                        Mejores Entradas
                    </button>
                    <button onclick="loadRankings('salida')" class="flex-1 bg-green-100 text-green-600 hover:bg-green-200 px-4 py-2 rounded-md">
                        Mejores Salidas
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Rankings -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posición</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Promedio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mejor Marca</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días a Tiempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Días</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="rankingTableBody">
                    <!-- Los datos se cargarán dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Establecer mes y año actual
    const currentDate = new Date();
    document.getElementById('monthSelect').value = currentDate.getMonth() + 1;
    document.getElementById('yearSelect').value = currentDate.getFullYear();
    
    // Cargar rankings iniciales (entradas por defecto)
    loadRankings('entrada');

    // Agregar event listeners para los selectores
    document.getElementById('monthSelect').addEventListener('change', () => loadRankings(currentType));
    document.getElementById('yearSelect').addEventListener('change', () => loadRankings(currentType));
});

let currentType = 'entrada';

function loadRankings(type) {
    currentType = type;
    const month = document.getElementById('monthSelect').value;
    const year = document.getElementById('yearSelect').value;

    fetch(`/admin/statistics/rankings?type=${type}&month=${month}&year=${year}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('rankingTableBody');
            tableBody.innerHTML = '';

            data.forEach((item, index) => {
                const row = document.createElement('tr');
                const positionClass = index < 3 ? 'text-green-600 font-bold' : 'text-gray-900';
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm ${positionClass}">#${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.average_time}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.best_time}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.on_time_days}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.total_days}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los rankings');
        });
}
</script>
@endpush
@endsection
