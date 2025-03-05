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
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('attendance.index', compact('todayAttendance', 'attendances'));
    }

    public function checkIn()
    {
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', $today)
            ->first();

        if ($attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has registrado tu entrada hoy'
            ]);
        }

        $attendance = Attendance::create([
            'user_id' => auth()->id(),
            'check_in' => now(),
            'status' => 'present'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Entrada registrada exitosamente'
        ]);
    }

    public function checkOut()
    {
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'No has registrado tu entrada hoy'
            ]);
        }

        if ($attendance->check_out) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has registrado tu salida'
            ]);
        }

        $attendance->check_out = now();
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Salida registrada exitosamente'
        ]);
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

    public function adminIndex()
    {
        $attendances = Attendance::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.attendances.index', compact('attendances'));
    }

    public function list(Request $request)
    {
        $user = auth()->user();
        $query = Attendance::where('user_id', $user->id);

        // Aplicar filtros de fecha si están presentes
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Ordenar por fecha más reciente
        $query->orderBy('created_at', 'desc');

        // Paginar los resultados
        $attendances = $query->paginate(10);

        return view('attendance.list', compact('attendances'));
    }

    public function breakStart(Request $request)
    {
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Debes marcar entrada primero'
            ]);
        }

        if ($attendance->break_start) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has iniciado tu almuerzo'
            ]);
        }

        $attendance->break_start = now();
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Almuerzo iniciado correctamente'
        ]);
    }

    public function breakEnd(Request $request)
    {
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', $today)
            ->first();

        if (!$attendance || !$attendance->break_start) {
            return response()->json([
                'success' => false,
                'message' => 'No has iniciado tu almuerzo'
            ]);
        }

        if ($attendance->break_end) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has finalizado tu almuerzo'
            ]);
        }

        $attendance->break_end = now();
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Almuerzo finalizado correctamente'
        ]);
    }
}
