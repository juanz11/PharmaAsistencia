<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RankingsExport;

class StatisticsController extends Controller
{
    public function index()
    {
        return view('admin.statistics.index');
    }

    public function getRankings(Request $request)
    {
        try {
            Log::info('Iniciando getRankings', [
                'request_params' => $request->all()
            ]);

            // Validar que los parámetros requeridos estén presentes
            if (!$request->has(['start_date', 'end_date', 'type'])) {
                Log::warning('Parámetros faltantes', [
                    'params' => $request->all()
                ]);
                return response()->json(['error' => 'Parámetros incompletos'], 400);
            }

            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $type = $request->type;
            $now = now();

            Log::info('Fechas procesadas', [
                'start_date' => $startDate->toDateTimeString(),
                'end_date' => $endDate->toDateTimeString(),
                'now' => $now->toDateTimeString(),
                'type' => $type
            ]);

            // Si la fecha de inicio es futura, retornar array vacío
            if ($startDate->isAfter($now)) {
                Log::info('Fecha inicio es futura');
                return response()->json([]);
            }

            // Si la fecha de fin es futura, ajustarla a ahora
            if ($endDate->isAfter($now)) {
                Log::info('Ajustando fecha fin a ahora');
                $endDate = $now;
            }

            // Obtener todos los usuarios empleados
            $users = User::where('role', 'employee')->get();
            
            Log::info('Usuarios encontrados', [
                'count' => $users->count()
            ]);

            if ($users->isEmpty()) {
                Log::warning('No se encontraron empleados');
                return response()->json([]);
            }

            $rankings = [];

            foreach ($users as $user) {
                try {
                    // Construir la consulta base
                    $query = Attendance::query()
                        ->where('user_id', $user->id)
                        ->whereBetween('created_at', [$startDate, $endDate]);

                    if ($type === 'entrada') {
                        $query->whereNotNull('check_in');
                    } else {
                        $query->whereNotNull('check_out');
                    }

                    $attendances = $query->get();

                    if ($attendances->isEmpty()) {
                        continue;
                    }

                    $onTimeDays = 0;
                    $totalMinutes = 0;
                    $attendanceDays = 0;
                    $bestTimeValue = null;
                    $bestTimeFormatted = null;

                    foreach ($attendances as $attendance) {
                        $currentDate = Carbon::parse($attendance->created_at);
                        
                        if ($currentDate->isAfter($now)) {
                            continue;
                        }

                        $attendanceDays++;

                        if ($type === 'entrada') {
                            $checkInTime = Carbon::parse($attendance->check_in);
                            $timeFormatted = $checkInTime->format('g:i A');
                            
                            // Hora límite de entrada: 8:40 AM
                            if ($checkInTime->format('H:i:s') <= '08:40:00') {
                                $onTimeDays++;
                            }

                            if (!$bestTimeValue || $checkInTime->format('H:i:s') < $bestTimeValue->format('H:i:s')) {
                                $bestTimeValue = $checkInTime;
                                $bestTimeFormatted = $timeFormatted;
                            }

                            $totalMinutes += $checkInTime->diffInMinutes(Carbon::parse($checkInTime->format('Y-m-d') . ' 08:00:00'));
                        } else {
                            if (!$attendance->check_out) continue;
                            
                            $checkOutTime = Carbon::parse($attendance->check_out);
                            $timeFormatted = $checkOutTime->format('g:i A');
                            
                            // Hora de salida: 4:55 PM - 5:00 PM se considera a tiempo
                            if ($checkOutTime->format('H:i:s') >= '16:55:00') {
                                $onTimeDays++;
                            }

                            if (!$bestTimeValue || $checkOutTime->format('H:i:s') > $bestTimeValue->format('H:i:s')) {
                                $bestTimeValue = $checkOutTime;
                                $bestTimeFormatted = $timeFormatted;
                            }

                            $totalMinutes += $checkOutTime->diffInMinutes(Carbon::parse($checkOutTime->format('Y-m-d') . ' 17:00:00'));
                        }

                        // Calcular horas trabajadas si tenemos check_in y check_out
                        if ($attendance->check_in && $attendance->check_out) {
                            $checkInTime = Carbon::parse($attendance->check_in);
                            $checkOutTime = Carbon::parse($attendance->check_out);
                            
                            // Obtener la duración del almuerzo
                            $lunchDuration = 60; // Por defecto 1 hora
                            if ($attendance->break_start && $attendance->break_end) {
                                $lunchDuration = Carbon::parse($attendance->break_start)
                                    ->diffInMinutes(Carbon::parse($attendance->break_end));
                            }
                            
                            // Calcular tiempo total trabajado (usando abs para asegurar valor positivo)
                            $totalWorkedMinutes = abs($checkOutTime->diffInMinutes($checkInTime));
                            $workedMinutes = floor($totalWorkedMinutes - $lunchDuration);
                            
                            // Agregar a las horas trabajadas totales
                            $attendance->working_minutes = $workedMinutes;
                        }
                    }

                    if ($bestTimeFormatted !== null && $attendanceDays > 0) {
                        // Calcular promedio de minutos trabajados
                        $totalWorkingMinutes = $attendances->sum('working_minutes');
                        $averageWorkingMinutes = $attendanceDays > 0 ? floor($totalWorkingMinutes / $attendanceDays) : 0;

                        // Formatear a HH:MM
                        $hours = floor($averageWorkingMinutes / 60);
                        $minutes = $averageWorkingMinutes % 60;
                        $formattedWorkingHours = sprintf('%02d:%02d', $hours, $minutes);

                        // Obtener el dispositivo más común para este usuario, excluyendo info del navegador
                        $commonDevice = Attendance::where('user_id', $user->id)
                            ->whereNotNull($type === 'entrada' ? 'check_in' : 'check_out')
                            ->select(DB::raw('SUBSTRING_INDEX(device, " - ", 1) as base_device'), DB::raw('count(*) as count'))
                            ->groupBy('base_device')
                            ->orderByDesc('count')
                            ->first();

                        // Obtener el dispositivo actual, excluyendo info del navegador
                        $currentDeviceFull = $attendances->last()->device;
                        $currentDevice = explode(' - ', $currentDeviceFull)[0];
                        
                        // Verificar si el equipo actual es diferente al común
                        $isUnusualDevice = $commonDevice && $currentDevice !== $commonDevice->base_device;

                        Log::info('Procesando usuario para rankings', [
                            'user' => $user->name,
                            'type' => $type,
                            'best_time' => $bestTimeFormatted,
                            'device' => $currentDeviceFull,
                            'base_device' => $currentDevice,
                            'common_device' => $commonDevice ? $commonDevice->base_device : null,
                            'is_unusual_device' => $isUnusualDevice,
                            'on_time_days' => $onTimeDays,
                            'total_days' => $attendanceDays
                        ]);

                        $rankings[] = [
                            'name' => $user->name,
                            'department' => $user->department,
                            'best_time' => $bestTimeFormatted,
                            'device' => $currentDeviceFull,
                            'is_unusual_device' => $isUnusualDevice,
                            'working_hours' => $formattedWorkingHours,
                            'working_minutes' => $averageWorkingMinutes, // Para comparación en frontend
                            'on_time_days' => $onTimeDays,
                            'total_days' => $attendanceDays
                        ];
                    }
                } catch (Exception $e) {
                    Log::error('Error procesando usuario', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            if (!empty($rankings)) {
                usort($rankings, function($a, $b) use ($type) {
                    $timeA = Carbon::createFromFormat('g:i A', $a['best_time'])->timestamp;
                    $timeB = Carbon::createFromFormat('g:i A', $b['best_time'])->timestamp;
                    
                    // Para entrada, el mejor tiempo es el más temprano
                    // Para salida, el mejor tiempo es el más tardío
                    if ($type === 'entrada') {
                        return $timeA - $timeB;
                    } else {
                        return $timeB - $timeA;
                    }
                });

                Log::info('Rankings ordenados', [
                    'type' => $type,
                    'count' => count($rankings),
                    'first_ranking' => $rankings[0] ?? null,
                    'last_ranking' => end($rankings) ?? null
                ]);
            }

            Log::info('Rankings generados exitosamente', [
                'count' => count($rankings)
            ]);

            return response()->json($rankings);

        } catch (Exception $e) {
            Log::error('Error en getRankings', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al procesar los rankings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download(Request $request)
    {
        try {
            if (!$request->has(['start_date', 'end_date', 'type', 'format'])) {
                return response()->json(['error' => 'Parámetros incompletos'], 400);
            }

            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $type = $request->type;
            $format = $request->format;

            // Obtener los datos del ranking
            $rankings = $this->getRankingsData($startDate, $endDate, $type);

            if (empty($rankings)) {
                return response()->json(['error' => 'No hay datos para exportar'], 404);
            }

            // Preparar el título del reporte
            $reportTitle = "Ranking de " . ucfirst($type) . " - ";
            $reportTitle .= $startDate->format('d/m/Y') === $endDate->format('d/m/Y') 
                ? $startDate->format('d/m/Y')
                : $startDate->format('d/m/Y') . " - " . $endDate->format('d/m/Y');

            if ($format === 'pdf') {
                $pdf = Pdf::loadView('admin.statistics.pdf', [
                    'rankings' => $rankings,
                    'title' => $reportTitle,
                    'type' => $type
                ]);
                
                return $pdf->download("ranking_{$type}_{$startDate->format('Y-m-d')}.pdf");
            } else {
                return Excel::download(
                    new RankingsExport($rankings, $reportTitle, $type),
                    "ranking_{$type}_{$startDate->format('Y-m-d')}.xlsx"
                );
            }
        } catch (Exception $e) {
            Log::error('Error en la descarga del reporte', [
                'error' => $e->getMessage(),
                'params' => $request->all()
            ]);
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    private function getRankingsData($startDate, $endDate, $type)
    {
        $rankings = [];
        $users = User::where('role', 'employee')->get();

        foreach ($users as $user) {
            $query = Attendance::query()
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate]);

            if ($type === 'entrada') {
                $query->whereNotNull('check_in');
            } else {
                $query->whereNotNull('check_out');
            }

            $attendances = $query->get();

            if ($attendances->isEmpty()) {
                continue;
            }

            $onTimeDays = 0;
            $totalDays = 0;
            $bestTimeValue = null;
            $bestTimeFormatted = null;

            foreach ($attendances as $attendance) {
                $totalDays++;

                if ($type === 'entrada') {
                    $checkTime = Carbon::parse($attendance->check_in);
                    if ($checkTime->format('H:i:s') <= '08:40:00') {
                        $onTimeDays++;
                    }
                    if (!$bestTimeValue || $checkTime->format('H:i:s') < $bestTimeValue->format('H:i:s')) {
                        $bestTimeValue = $checkTime;
                        $bestTimeFormatted = $checkTime->format('g:i A');
                    }
                } else {
                    $checkTime = Carbon::parse($attendance->check_out);
                    if ($checkTime->format('H:i:s') >= '16:55:00') {
                        $onTimeDays++;
                    }
                    if (!$bestTimeValue || $checkTime->format('H:i:s') > $bestTimeValue->format('H:i:s')) {
                        $bestTimeValue = $checkTime;
                        $bestTimeFormatted = $checkTime->format('g:i A');
                    }
                }

                // Calcular horas trabajadas si tenemos check_in y check_out
                if ($attendance->check_in && $attendance->check_out) {
                    $checkInTime = Carbon::parse($attendance->check_in);
                    $checkOutTime = Carbon::parse($attendance->check_out);
                    
                    // Obtener la duración del almuerzo
                    $lunchDuration = 60; // Por defecto 1 hora
                    if ($attendance->break_start && $attendance->break_end) {
                        $lunchDuration = Carbon::parse($attendance->break_start)
                            ->diffInMinutes(Carbon::parse($attendance->break_end));
                    }
                    
                    // Calcular tiempo total trabajado (usando abs para asegurar valor positivo)
                    $totalWorkedMinutes = abs($checkOutTime->diffInMinutes($checkInTime));
                    $workedMinutes = floor($totalWorkedMinutes - $lunchDuration);
                    
                    // Agregar a las horas trabajadas totales
                    $attendance->working_minutes = $workedMinutes;
                }
            }

            if ($bestTimeFormatted !== null) {
                // Calcular promedio de minutos trabajados
                $totalWorkingMinutes = $attendances->sum('working_minutes');
                $averageWorkingMinutes = $totalDays > 0 ? floor($totalWorkingMinutes / $totalDays) : 0;

                // Formatear a HH:MM
                $hours = floor($averageWorkingMinutes / 60);
                $minutes = $averageWorkingMinutes % 60;
                $formattedWorkingHours = sprintf('%02d:%02d', $hours, $minutes);

                // Obtener el dispositivo más común
                $commonDevice = Attendance::where('user_id', $user->id)
                    ->whereNotNull($type === 'entrada' ? 'check_in' : 'check_out')
                    ->select('device', DB::raw('count(*) as count'))
                    ->groupBy('device')
                    ->orderByDesc('count')
                    ->first();

                $currentDevice = $attendances->last()->device;
                $isUnusualDevice = $commonDevice && $currentDevice !== $commonDevice->device;

                $rankings[] = [
                    'name' => $user->name,
                    'department' => $user->department,
                    'best_time' => $bestTimeFormatted,
                    'device' => $currentDevice,
                    'is_unusual_device' => $isUnusualDevice,
                    'working_hours' => $formattedWorkingHours,
                    'working_minutes' => $averageWorkingMinutes, // Para comparación en frontend
                    'on_time_days' => $onTimeDays,
                    'total_days' => $totalDays,
                    'percentage' => number_format(($onTimeDays / $totalDays) * 100, 1)
                ];
            }
        }

        // Ordenar rankings
        usort($rankings, function($a, $b) use ($type) {
            $timeA = Carbon::createFromFormat('g:i A', $a['best_time'])->timestamp;
            $timeB = Carbon::createFromFormat('g:i A', $b['best_time'])->timestamp;
            
            return $type === 'entrada' ? $timeA - $timeB : $timeB - $timeA;
        });

        // Agregar posición
        foreach ($rankings as $index => $ranking) {
            $rankings[$index]['position'] = $index + 1;
        }

        return $rankings;
    }
}
