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

    public function checkIn(Request $request)
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
            'status' => 'present',
            'device' => $this->getDeviceInfo($request),
            'ip_address' => $this->getIpAddress($request)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Entrada registrada exitosamente'
        ]);
    }

    public function checkOut(Request $request)
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
        $attendance->device = $this->getDeviceInfo($request);
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Salida registrada exitosamente'
        ]);
    }

    private function getDeviceInfo(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        $device = 'Desconocido';
        
        if (strpos($userAgent, 'Mobile') !== false) {
            $device = 'Móvil';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            $device = 'Tablet';
        } else {
            $device = 'Computadora';
        }
        
        return $device;
    }

    private function getIpAddress(Request $request)
    {
        // Intentamos obtener la IP real del cliente revisando varios headers
        foreach ([
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ] as $key) {
            if ($request->server($key)) {
                $ips = explode(',', $request->server($key));
                $ip = trim(end($ips)); // Tomamos la última IP en caso de que haya varias
                
                // Validamos que sea una IP válida y no sea una IP privada
                if (filter_var($ip, FILTER_VALIDATE_IP, 
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        // Si no encontramos una IP válida en los headers, usamos el método ip()
        return $request->ip();
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
