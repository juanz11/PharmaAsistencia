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
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());
        $selectedUserId = $request->input('user_id');

        // Obtener lista de usuarios para el select
        $users = User::orderBy('name')->get();

        // Query base para asistencias
        $query = Attendance::query();

        // Aplicar filtros
        $query->whereBetween('date', [$startDate, $endDate]);
        if ($selectedUserId) {
            $query->where('user_id', $selectedUserId);
        }

        // Obtener estadísticas de asistencia
        $attendanceStats = $query->select(
                'user_id',
                DB::raw('COUNT(*) as total_days'),
                DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days'),
                DB::raw('AVG(CASE WHEN check_in_time IS NOT NULL THEN TIME_TO_SEC(check_in_time) ELSE NULL END) as avg_check_in_seconds')
            )
            ->groupBy('user_id')
            ->with('user:id,name')
            ->get();

        // Obtener datos para la gráfica
        $dailyAttendance = $query->select(
                'date',
                DB::raw('COUNT(*) as total_attendance'),
                DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Obtener lista detallada de asistencias
        $attendances = $query->with('user')
            ->latest('date')
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
