<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .header h1 {
            font-size: 16px;
            color: #2c3e50;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background: white;
        }
        th {
            background-color: #4b6584;
            color: white;
            font-weight: bold;
            padding: 6px 4px;
            font-size: 10px;
            text-transform: uppercase;
            text-align: center;
        }
        td {
            border: 1px solid #dee2e6;
            padding: 5px 4px;
            text-align: center;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .position {
            font-weight: bold;
            color: #2c3e50;
        }
        .name {
            text-align: left;
            font-weight: 500;
        }
        .percentage {
            font-weight: bold;
        }
        .unusual-device {
            color: #e74c3c;
            font-weight: bold;
        }
        .good-percentage {
            color: #27ae60;
        }
        .bad-percentage {
            color: #c0392b;
        }
        .footer {
            text-align: right;
            font-size: 8px;
            color: #7f8c8d;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
        }
        .type-info {
            text-align: left;
            font-size: 10px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        .type-info strong {
            color: #34495e;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Página de Entradas -->
    <div class="header">
        <h1>Ranking de Entrada - {{ $startDate }} - {{ $endDate }}</h1>
    </div>

    <div class="type-info">
        <strong>Tipo de Registro:</strong> Entrada<br>
        <strong>Horario Esperado:</strong> 8:40 AM
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>NOMBRE</th>
                <th>MEJOR TIEMPO</th>
                <th>DISPOSITIVO</th>
                <th>DÍAS A TIEMPO</th>
                <th>TOTAL DÍAS</th>
                <th>HORAS TRABAJADAS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rankingsEntrada as $index => $entry)
                <tr>
                    <td class="position">{{ $index + 1 }}</td>
                    <td class="name">{{ $entry['name'] }}</td>
                    <td>{{ $entry['best_time'] ?? '-' }}</td>
                    <td class="{{ $entry['is_unusual_device'] ? 'unusual-device' : '' }}">
                        {{ $entry['device'] ? explode(' - ', $entry['device'])[0] : '-' }}
                    </td>
                    <td class="{{ ($entry['on_time_days'] / ($entry['total_days'] ?: 1)) >= 0.8 ? 'good-percentage' : 'bad-percentage' }}">
                        {{ $entry['on_time_days'] }}
                    </td>
                    <td>{{ $entry['total_days'] }}</td>
                    <td>{{ $entry['working_hours'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- Página de Salidas -->
    <div class="header">
        <h1>Ranking de Salida - {{ $startDate }} - {{ $endDate }}</h1>
    </div>

    <div class="type-info">
        <strong>Tipo de Registro:</strong> Salida<br>
        <strong>Horario Esperado:</strong> 5:00 PM
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>NOMBRE</th>
                <th>MEJOR TIEMPO</th>
                <th>DISPOSITIVO</th>
                <th>DÍAS A TIEMPO</th>
                <th>TOTAL DÍAS</th>
                <th>HORAS TRABAJADAS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rankingsSalida as $index => $entry)
                <tr>
                    <td class="position">{{ $index + 1 }}</td>
                    <td class="name">{{ $entry['name'] }}</td>
                    <td>{{ $entry['best_time'] ?? '-' }}</td>
                    <td class="{{ $entry['is_unusual_device'] ? 'unusual-device' : '' }}">
                        {{ $entry['device'] ? explode(' - ', $entry['device'])[0] : '-' }}
                    </td>
                    <td class="{{ ($entry['on_time_days'] / ($entry['total_days'] ?: 1)) >= 0.8 ? 'good-percentage' : 'bad-percentage' }}">
                        {{ $entry['on_time_days'] }}
                    </td>
                    <td>{{ $entry['total_days'] }}</td>
                    <td>{{ $entry['working_hours'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
