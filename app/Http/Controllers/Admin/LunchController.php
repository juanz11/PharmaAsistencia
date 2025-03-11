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
        return view('admin.lunch.index', [
            'lunchData' => $this->getLunchData(now(), now())
        ]);
    }

    public function getLunchRecords(Request $request)
    {
        $startDate = Carbon::parse($request->start);
        $endDate = Carbon::parse($request->end);

        $records = $this->getLunchData($startDate, $endDate);
        $stats = $this->calculateStats($records);

        return response()->json([
            'records' => $records,
            'stats' => $stats
        ]);
    }

    private function getLunchData($startDate, $endDate)
    {
        return Attendance::whereBetween('created_at', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ])
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
                            $statusClass = 'text-warning';
                        } elseif ($duration > 60) {
                            $status = 'Tiempo excedido';
                            $statusClass = 'text-danger';
                        } else {
                            $status = 'Completado';
                            $statusClass = 'text-success';
                        }
                    } else {
                        $duration = Carbon::parse($attendance->break_start)->diffInMinutes(now());
                        $status = 'En progreso';
                        $statusClass = 'text-primary';
                    }
                }

                return [
                    'id' => $attendance->id,
                    'user' => $attendance->user->name,
                    'date' => Carbon::parse($attendance->created_at)->format('d/m/Y'),
                    'start' => $attendance->break_start ? Carbon::parse($attendance->break_start)->format('h:i A') : '--:--',
                    'end' => $attendance->break_end ? Carbon::parse($attendance->break_end)->format('h:i A') : '--:--',
                    'duration' => $duration ? $this->formatDuration($duration) : '--:--',
                    'duration_minutes' => $duration,
                    'status' => $status,
                    'statusClass' => $statusClass
                ];
            });
    }

    private function calculateStats($records)
    {
        $completedCount = 0;
        $exceededCount = 0;
        $shortCount = 0;
        $totalDuration = 0;
        $validRecords = 0;

        foreach ($records as $record) {
            if ($record['duration_minutes']) {
                $totalDuration += $record['duration_minutes'];
                $validRecords++;

                if ($record['duration_minutes'] >= 30 && $record['duration_minutes'] <= 60) {
                    $completedCount++;
                } elseif ($record['duration_minutes'] > 60) {
                    $exceededCount++;
                } else {
                    $shortCount++;
                }
            }
        }

        $averageDuration = $validRecords > 0 ? round($totalDuration / $validRecords) : 0;

        return [
            'averageDuration' => $this->formatDuration($averageDuration),
            'completedCount' => $completedCount,
            'exceededCount' => $exceededCount,
            'shortCount' => $shortCount
        ];
    }

    private function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return sprintf("%02d:%02d", $hours, $remainingMinutes);
    }
}
