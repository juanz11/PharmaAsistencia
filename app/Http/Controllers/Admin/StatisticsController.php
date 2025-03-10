<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        return view('admin.statistics.index');
    }

    public function getAttendanceData()
    {
        $users = User::where('role', 'employee')->get();
        $attendanceData = [];

        foreach ($users as $user) {
            // Calculate attendance percentage for the last 30 days
            $totalDays = 30;
            $attendedDays = Attendance::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDays($totalDays))
                ->whereNotNull('check_in')
                ->whereNotNull('check_out')
                ->count();

            $percentage = ($attendedDays / $totalDays) * 100;

            $attendanceData[] = [
                'name' => $user->name,
                'attendance' => round($percentage, 2),
                'attended_days' => $attendedDays,
                'total_days' => $totalDays
            ];
        }

        return response()->json($attendanceData);
    }

    public function getRankings(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        $type = $request->query('type', 'entrada');

        // Crear fecha de inicio y fin del mes
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $users = User::where('role', 'employee')->get();
        $rankings = [];

        foreach ($users as $user) {
            $attendances = Attendance::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('check_in')
                ->when($type === 'salida', function ($query) {
                    return $query->whereNotNull('check_out');
                })
                ->get();

            if ($attendances->isEmpty()) {
                continue;
            }

            $onTimeDays = 0;
            $bestTime = null;
            $totalMinutes = 0;

            foreach ($attendances as $attendance) {
                if ($type === 'entrada') {
                    $time = Carbon::parse($attendance->check_in)->format('g:i A');
                    $minutes = Carbon::parse($attendance->check_in)->diffInMinutes(Carbon::parse('08:00:00'));
                    
                    // Considera a tiempo si llega antes de las 8:00 AM
                    if (Carbon::parse($attendance->check_in) <= Carbon::parse('08:00:00')) {
                        $onTimeDays++;
                    }

                    // El mejor tiempo es el más temprano
                    if (!$bestTime || Carbon::parse($attendance->check_in)->format('H:i:s') < Carbon::parse($bestTime)->format('H:i:s')) {
                        $bestTime = $time;
                    }
                } else {
                    if (!$attendance->check_out) continue;
                    
                    $time = Carbon::parse($attendance->check_out)->format('g:i A');
                    $minutes = Carbon::parse($attendance->check_out)->diffInMinutes(Carbon::parse('17:00:00'));
                    
                    // Considera a tiempo si sale después de las 5:00 PM
                    if (Carbon::parse($attendance->check_out) >= Carbon::parse('17:00:00')) {
                        $onTimeDays++;
                    }

                    // El mejor tiempo es el más tarde
                    if (!$bestTime || Carbon::parse($attendance->check_out)->format('H:i:s') > Carbon::parse($bestTime)->format('H:i:s')) {
                        $bestTime = $time;
                    }
                }

                $totalMinutes += $minutes;
            }

            $averageTime = $totalMinutes / $attendances->count();
            $averageHour = floor($averageTime / 60);
            $averageMinute = $averageTime % 60;
            
            // Convertir el promedio a formato 12 horas con AM/PM
            $averageTimeFormatted = Carbon::createFromTime($averageHour, $averageMinute)->format('g:i A');

            $rankings[] = [
                'name' => $user->name,
                'average_time' => $averageTimeFormatted,
                'best_time' => $bestTime,
                'on_time_days' => $onTimeDays,
                'total_days' => $attendances->count()
            ];
        }

        // Ordenar rankings
        if ($type === 'entrada') {
            // Para entradas, ordenar por más temprano (menor tiempo)
            usort($rankings, function($a, $b) {
                return Carbon::createFromFormat('g:i A', $a['best_time'])->timestamp - 
                       Carbon::createFromFormat('g:i A', $b['best_time'])->timestamp;
            });
        } else {
            // Para salidas, ordenar por más tarde (mayor tiempo)
            usort($rankings, function($a, $b) {
                return Carbon::createFromFormat('g:i A', $b['best_time'])->timestamp - 
                       Carbon::createFromFormat('g:i A', $a['best_time'])->timestamp;
            });
        }

        return response()->json($rankings);
    }
}
