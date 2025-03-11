<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LunchController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        $today = Carbon::now();
        
        $lunchData = Attendance::whereDate('created_at', $today)
            ->whereNotNull('break_start')
            ->with('user')
            ->get()
            ->map(function ($attendance) {
                $duration = null;
                $status = 'No iniciado';
                $statusClass = 'text-gray-500';

                if ($attendance->break_start) {
                    if ($attendance->break_end) {
                        $duration = Carbon::parse($attendance->break_start)
                            ->diffInMinutes(Carbon::parse($attendance->break_end));
                        
                        if ($duration < 30) {
                            $status = 'Tiempo corto';
                            $statusClass = 'text-yellow-500';
                        } elseif ($duration > 60) {
                            $status = 'Tiempo excedido';
                            $statusClass = 'text-red-500';
                        } else {
                            $status = 'Completado';
                            $statusClass = 'text-green-500';
                        }
                    } else {
                        $duration = Carbon::parse($attendance->break_start)->diffInMinutes(now());
                        $status = 'En progreso';
                        $statusClass = 'text-blue-500';
                    }
                }

                return [
                    'id' => $attendance->id,
                    'user' => $attendance->user->name,
                    'start' => $attendance->break_start ? Carbon::parse($attendance->break_start)->format('h:i A') : '--:--',
                    'end' => $attendance->break_end ? Carbon::parse($attendance->break_end)->format('h:i A') : '--:--',
                    'duration' => $duration ? $this->formatDuration($duration) : '--:--',
                    'status' => $status,
                    'statusClass' => $statusClass
                ];
            });

        return view('admin.lunch.index', compact('lunchData', 'users'));
    }

    private function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return sprintf("%02d:%02d", $hours, $remainingMinutes);
    }

    public function updateLunchTime(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        
        if ($request->has('break_start')) {
            $attendance->break_start = $request->break_start;
        }
        
        if ($request->has('break_end')) {
            $attendance->break_end = $request->break_end;
        }
        
        $attendance->save();
        
        return response()->json(['success' => true]);
    }
}
