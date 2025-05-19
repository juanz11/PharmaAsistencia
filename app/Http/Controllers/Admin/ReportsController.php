<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $selectedUserId = $request->get('user_id');

        $query = $this->getAttendanceQuery($startDate, $endDate, $selectedUserId);
        $stats = $query->get()->map(function ($stat) {
            return (object) [
                'name' => $stat->employee_name,
                'device' => $stat->most_used_device ?? 'No registrado',
                'total_hours' => floor(($stat->total_minutes_worked - 60) / 60) . ':' . str_pad(($stat->total_minutes_worked - 60) % 60, 2, '0', STR_PAD_LEFT),
                'total_days' => $stat->total_days
            ];
        });

        $fileName = 'reporte_asistencias_' . date('Y-m-d') . '.xlsx';
        return Excel::download(new \App\Exports\AttendanceReportExport($stats), $fileName);
    }

    private function getAttendanceQuery($startDate, $endDate, $selectedUserId = null)
    {
        $query = Attendance::query()
            ->select([
                'users.name as employee_name',
                DB::raw('COUNT(DISTINCT DATE(attendances.created_at)) as total_days'),
                DB::raw('(
                    SELECT device
                    FROM attendances a2
                    WHERE a2.user_id = attendances.user_id
                    GROUP BY device
                    ORDER BY COUNT(*) DESC
                    LIMIT 1
                ) as most_used_device'),
                DB::raw('SUM(
                    CASE 
                        WHEN check_in IS NOT NULL AND check_out IS NOT NULL THEN
                            LEAST(
                                TIMESTAMPDIFF(
                                    MINUTE,
                                    GREATEST(
                                        check_in,
                                        CONCAT(DATE(check_in), " 08:00:00")
                                    ),
                                    LEAST(
                                        check_out,
                                        CONCAT(DATE(check_out), " 17:00:00")
                                    )
                                ),
                                540  -- 9 horas (17:00 - 8:00 = 540 minutos)
                            )
                        ELSE 0
                    END
                ) as total_minutes_worked')
            ])
            ->join('users', 'users.id', '=', 'attendances.user_id')
            ->whereBetween('attendances.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->where('users.role', 'employee')
            ->groupBy('users.id', 'users.name');

        if ($selectedUserId) {
            $query->where('users.id', $selectedUserId);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $selectedUserId = $request->get('user_id');

        // Obtener usuarios
        $users = User::where('role', 'employee')->get();

        // Consulta base
        $query = Attendance::query()
            ->select([
                'users.name as employee_name',
                DB::raw('COUNT(DISTINCT DATE(attendances.created_at)) as total_days'),
                DB::raw('(
                    SELECT device
                    FROM attendances a2
                    WHERE a2.user_id = attendances.user_id
                    GROUP BY device
                    ORDER BY COUNT(*) DESC
                    LIMIT 1
                ) as most_used_device'),
                DB::raw('SUM(
                    CASE 
                        WHEN check_in IS NOT NULL AND check_out IS NOT NULL THEN
                            LEAST(
                                TIMESTAMPDIFF(
                                    MINUTE,
                                    GREATEST(
                                        check_in,
                                        CONCAT(DATE(check_in), " 08:00:00")
                                    ),
                                    LEAST(
                                        check_out,
                                        CONCAT(DATE(check_out), " 17:00:00")
                                    )
                                ),
                                540  -- 9 horas (17:00 - 8:00 = 540 minutos)
                            )
                        ELSE 0
                    END
                ) as total_minutes_worked')
            ])
            ->join('users', 'users.id', '=', 'attendances.user_id')
            ->whereBetween('attendances.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->where('users.role', 'employee')
            ->groupBy('users.id', 'users.name');

        // Filtrar por usuario si se especifica
        if ($selectedUserId) {
            $query->where('users.id', $selectedUserId);
        }

        $attendanceStats = $query->get()->map(function ($stat) {
            return (object) [
                'name' => $stat->employee_name,
                'device' => $stat->most_used_device ?? 'No registrado',
                'total_hours' => floor(($stat->total_minutes_worked - 60) / 60) . ':' . str_pad(($stat->total_minutes_worked - 60) % 60, 2, '0', STR_PAD_LEFT),
                'total_days' => $stat->total_days
            ];
        });

        // Obtener datos para el gráfico
        $dailyAttendance = $this->getDailyAttendanceData($startDate, $endDate, $selectedUserId);

        // Obtener registros detallados
        $attendances = $this->getDetailedAttendance($startDate, $endDate, $selectedUserId);

        return view('admin.reports.index', compact(
            'users',
            'selectedUserId',
            'attendanceStats',
            'dailyAttendance',
            'attendances'
        ));
    }

    private function getDailyAttendanceData($startDate, $endDate, $userId = null)
    {
        $query = Attendance::select([
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_attendance'),
            DB::raw('COUNT(CASE WHEN status = "present" THEN 1 END) as present_count')
        ])
        ->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ])
        ->groupBy(DB::raw('DATE(created_at)'));

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->get();
    }

    private function getDetailedAttendance($startDate, $endDate, $userId = null)
    {
        $query = Attendance::with('user')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->orderBy('created_at', 'desc');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->paginate(15);
    }
}
