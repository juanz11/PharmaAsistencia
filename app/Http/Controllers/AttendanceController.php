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
        
        // Obtener la asistencia del día actual
        $todayAttendance = $user->attendances()
            ->where('date', Carbon::today()->toDateString())
            ->first();

        // Obtener el historial de asistencias
        $attendances = $user->attendances()
            ->latest('check_in_time')
            ->paginate(10);

        return view('attendance.index', compact('todayAttendance', 'attendances'));
    }

    public function checkIn(Request $request)
    {
        $user = auth()->user();
        
        // Verificar si ya tiene un registro de asistencia hoy
        $existingAttendance = $user->attendances()
            ->where('date', Carbon::today()->toDateString())
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'Ya has registrado tu entrada hoy.');
        }

        $now = now();
        $attendance = new Attendance([
            'date' => $now->toDateString(),
            'check_in_time' => $now,
            'notes' => $request->notes,
            'status' => 'present',
            'check_in_device' => $this->getDeviceInfo($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $user->attendances()->save($attendance);

        return back()->with('success', 'Entrada registrada exitosamente.');
    }

    private function getDeviceInfo(Request $request)
    {
        $agent = $request->userAgent();
        $device = 'Unknown';

        if (preg_match('/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i', $agent)) {
            $device = 'Mobile';
        } elseif (preg_match('/(linux|mac|windows)/i', $agent)) {
            $device = 'Desktop';
        }

        return $device;
    }

    public function checkOut(Attendance $attendance)
    {
        if ($attendance->user_id !== auth()->id()) {
            return back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Ya has registrado tu salida.');
        }

        $now = now();
        $attendance->update([
            'check_out_time' => $now,
            'notes' => request('notes')
        ]);

        return back()->with('success', 'Salida registrada exitosamente.');
    }

    public function adminIndex()
    {
        // Obtener todas las asistencias con información del usuario
        $attendances = Attendance::with('user')
            ->orderBy('date', 'desc')
            ->orderBy('check_in_time', 'desc')
            ->paginate(15);

        return view('admin.attendances.index', compact('attendances'));
    }

    public function list(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->attendances()->latest('date');

        // Aplicar filtros de fecha si están presentes
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        // Paginar los resultados
        $attendances = $query->paginate(10);

        // Si es una solicitud AJAX, devolver solo la vista parcial
        if ($request->ajax()) {
            return view('attendance.list-partial', compact('attendances'));
        }

        return view('attendance.list', compact('attendances'));
    }
}
