<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Obtener la asistencia del día actual
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        return view('attendance.index', compact('attendance'));
    }

    protected function getDeviceInfo(Request $request)
    {
        $agent = new Agent();
        $device = $agent->device();
        $platform = $agent->platform();
        $browser = $agent->browser();

        return [
            'device' => $device ? "{$device} ({$platform})" : "Desconocido",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ];
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->toDateString();

        // Verificar si ya existe un registro para hoy
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance) {
            return redirect()->back()->with('error', 'Ya has registrado tu entrada hoy.');
        }

        // Determinar si es tarde (después de las 9 AM)
        $status = $now->hour >= 9 ? 'late' : 'present';

        // Obtener información del dispositivo
        $deviceInfo = $this->getDeviceInfo($request);

        // Crear nuevo registro de asistencia
        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in_time' => $now,
            'status' => $status,
            'notes' => $request->notes,
            'check_in_device' => $deviceInfo['device'],
            'ip_address' => $deviceInfo['ip_address'],
            'user_agent' => $deviceInfo['user_agent']
        ]);

        return redirect()->back()->with('success', 'Entrada registrada correctamente.');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'No has registrado tu entrada hoy.');
        }

        if ($attendance->check_out_time) {
            return redirect()->back()->with('error', 'Ya has registrado tu salida hoy.');
        }

        // Obtener información del dispositivo
        $deviceInfo = $this->getDeviceInfo($request);

        $attendance->update([
            'check_out_time' => Carbon::now(),
            'check_out_device' => $deviceInfo['device'],
            'notes' => $request->notes ? $attendance->notes . "\n" . $request->notes : $attendance->notes
        ]);

        return redirect()->back()->with('success', 'Salida registrada correctamente.');
    }
}
