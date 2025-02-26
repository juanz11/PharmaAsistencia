<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $attendances = $user->attendances()
            ->latest('check_in_time')
            ->paginate(10);

        return view('attendance.index', compact('attendances'));
    }

    public function checkIn(Request $request)
    {
        $user = auth()->user();
        
        // Verificar si ya tiene un registro de asistencia hoy
        $existingAttendance = $user->attendances()
            ->whereDate('check_in_time', Carbon::today())
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'Ya has registrado tu entrada hoy.');
        }

        $attendance = new Attendance([
            'check_in_time' => now(),
            'notes' => $request->notes
        ]);

        $user->attendances()->save($attendance);

        return back()->with('success', 'Entrada registrada exitosamente.');
    }

    public function checkOut(Attendance $attendance)
    {
        if ($attendance->user_id !== auth()->id()) {
            return back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Ya has registrado tu salida.');
        }

        $attendance->update([
            'check_out' => now()
        ]);

        return back()->with('success', 'Salida registrada exitosamente.');
    }
}
