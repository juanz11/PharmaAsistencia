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
                            
                            if ($checkInTime->format('H:i:s') <= '08:00:00') {
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
                            
                            if ($checkOutTime->format('H:i:s') >= '17:00:00') {
                                $onTimeDays++;
                            }

                            if (!$bestTimeValue || $checkOutTime->format('H:i:s') > $bestTimeValue->format('H:i:s')) {
                                $bestTimeValue = $checkOutTime;
                                $bestTimeFormatted = $timeFormatted;
                            }

                            $totalMinutes += $checkOutTime->diffInMinutes(Carbon::parse($checkOutTime->format('Y-m-d') . ' 17:00:00'));
                        }
                    }

                    if ($bestTimeFormatted !== null && $attendanceDays > 0) {
                        $averageTime = $totalMinutes / $attendanceDays;
                        $averageHour = floor($averageTime / 60);
                        $averageMinute = $averageTime % 60;
                        $averageTimeFormatted = Carbon::createFromTime($averageHour, $averageMinute)->format('g:i A');

                        $rankings[] = [
                            'name' => $user->name,
                            'average_time' => $averageTimeFormatted,
                            'best_time' => $bestTimeFormatted,
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
                    return $type === 'entrada' ? $timeA - $timeB : $timeB - $timeA;
                });
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
}
