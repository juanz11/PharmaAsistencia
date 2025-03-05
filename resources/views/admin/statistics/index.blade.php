@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Estadísticas de Asistencia</h2>
        
        <div class="mb-8">
            <div class="bg-white p-4 rounded-lg shadow">
                <canvas id="attendanceChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden" style="
    width: 100%;
        ">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asistencia (%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días Asistidos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Días</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="attendanceTableBody">
                    <!-- Los datos de la tabla se cargarán aquí -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/admin/statistics/data')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.name),
                    datasets: [{
                        label: 'Porcentaje de Asistencia',
                        data: data.map(item => item.attendance),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Porcentaje de Asistencia'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Empleados'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Estadísticas de Asistencia (Últimos 30 días)'
                        }
                    }
                }
            });

            // Llenar la tabla con los datos
            const tableBody = document.getElementById('attendanceTableBody');
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.attendance}%</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.attended_days}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.total_days}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error));
});
</script>
@endpush
@endsection
