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
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
    </div>

    <div class="type-info">
        <strong>Tipo de Registro:</strong> {{ ucfirst($type) }}<br>
        <strong>Horario Esperado:</strong> 
        @if($type === 'entrada')
            8:40 AM
        @else
            4:55 PM
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%">Posición</th>
                <th width="25%">Nombre</th>
                <th width="15%">Mejor Tiempo</th>
                <th width="20%">Dispositivo</th>
                <th width="10%">Días a Tiempo</th>
                <th width="10%">Total Días</th>
                <th width="12%">Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rankings as $ranking)
                <tr>
                    <td class="position">{{ $ranking['position'] }}</td>
                    <td class="name">{{ $ranking['name'] }}</td>
                    <td>{{ $ranking['best_time'] }}</td>
                    <td class="{{ $ranking['is_unusual_device'] ? 'unusual-device' : '' }}">
                        {{ $ranking['device'] }}
                    </td>
                    <td>{{ $ranking['on_time_days'] }}</td>
                    <td>{{ $ranking['total_days'] }}</td>
                    <td class="percentage {{ $ranking['percentage'] >= 80 ? 'good-percentage' : ($ranking['percentage'] < 60 ? 'bad-percentage' : '') }}">
                        {{ $ranking['percentage'] }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Reporte generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
