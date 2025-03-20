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
        // Verificar si es un dispositivo móvil
        if ($this->isMobileDevice($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Este sistema sólo se encuentra habilitado para equipos de la organización.'
            ], 403);
        }

        $today = now();
        $user = auth()->user();

        // Verificar si ya existe un registro para hoy
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has registrado tu entrada hoy'
            ]);
        }

        // Crear nuevo registro de asistencia
        $attendance = new Attendance([
            'user_id' => $user->id,
            'date' => $today->toDateString(),
            'check_in' => $today,
            'status' => 'present',
            'device' => $request->input('device', 'Unknown'),
            'ip_address' => $request->ip()
        ]);

        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Entrada registrada exitosamente',
            'time' => $today->format('g:i A')
        ]);
    }

    public function checkOut(Request $request)
    {
        // Verificar si es un dispositivo móvil
        if ($this->isMobileDevice($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Tienes que marcar dentro de las instalaciones. Por favor, utiliza una computadora registrada.'
            ], 403);
        }

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
        $query = Attendance::query()->where('user_id', auth()->id());

        // Filtrar por rango de fechas si se proporcionan
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Ordenar por fecha más reciente
        $query->orderBy('date', 'desc')
              ->orderBy('check_in', 'desc');

        // Paginar los resultados
        $attendances = $query->paginate(10);

        return view('attendance.list', compact('attendances'));
    }

    public function breakStart(Request $request)
    {
        // Verificar si es un dispositivo móvil
        if ($this->isMobileDevice($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Este sistema sólo se encuentra habilitado para equipos de la organización.'
            ], 403);
        }

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
        // Verificar si es un dispositivo móvil
        if ($this->isMobileDevice($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Este sistema sólo se encuentra habilitado para equipos de la organización.'
            ], 403);
        }

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

    /**
     * Determina si la solicitud proviene de un dispositivo móvil y si debe ser bloqueada
     */
    private function isMobileDevice(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        $mobileKeywords = ['Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone', 'webOS', 'BlackBerry', 'Opera Mini'];
        $isMobile = false;
        
        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                $isMobile = true;
                break;
            }
        }

        // Si no es móvil, permitir acceso
        if (!$isMobile) {
            return false;
        }

        // Si es móvil, verificar si el usuario es del departamento comercial
        $user = auth()->user();
        if ($user && $user->department === 'COMERCIAL') {
            return false;
        }

        // Para cualquier otro caso, bloquear acceso móvil
        return true;
    }
}
