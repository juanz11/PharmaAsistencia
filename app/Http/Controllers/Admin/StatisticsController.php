<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
