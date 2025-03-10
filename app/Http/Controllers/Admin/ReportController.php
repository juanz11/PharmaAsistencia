<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfDay();
        $selectedUserId = $request->input('user_id');

        // Obtener lista de usuarios para el select
        $users = User::orderBy('name')->get();

        // Query base para asistencias
        $query = Attendance::query();

        // Aplicar filtros
        $query->whereBetween('created_at', [$startDate, $endDate]);
        if ($selectedUserId) {
            $query->where('user_id', $selectedUserId);
        }

        // Obtener estadísticas de asistencia
        $attendanceStats = $query->select(
                'user_id',
                DB::raw('COUNT(*) as total_days'),
                DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days'),
                DB::raw('AVG(CASE WHEN check_in IS NOT NULL THEN HOUR(check_in) * 3600 + MINUTE(check_in) * 60 + SECOND(check_in) ELSE NULL END) as avg_check_in_seconds')
            )
            ->groupBy('user_id')
            ->with('user:id,name')
            ->get()
            ->filter(function ($stat) {
                return $stat->user !== null;
            });

        // Obtener datos para la gráfica
        $dailyAttendance = $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_attendance'),
                DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Obtener lista detallada de asistencias
        $attendances = Attendance::with('user')
            ->when($selectedUserId, function($q) use ($selectedUserId) {
                return $q->where('user_id', $selectedUserId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest('created_at')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('admin.reports.index', compact(
            'attendanceStats',
            'dailyAttendance',
            'attendances',
            'startDate',
            'endDate',
            'users',
            'selectedUserId'
        ));
    }
}
