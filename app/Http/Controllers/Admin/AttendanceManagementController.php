<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceManagementController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'employee')->get();
        return view('admin.attendance.index', compact('users'));
    }

    public function userAttendance($userId)
    {
        $user = User::findOrFail($userId);
        $attendances = Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->get();
        
        return view('admin.attendance.user', compact('user', 'attendances'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        
        $request->validate([
            'check_in' => 'required',
            'check_out' => 'required|after:check_in',
        ]);

        try {
            // Convertir las fechas a la zona horaria de Venezuela
            $checkIn = Carbon::parse($request->check_in)->setTimezone('America/Caracas');
            $checkOut = Carbon::parse($request->check_out)->setTimezone('America/Caracas');

            $attendance->update([
                'date' => $checkIn->toDateString(),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
            ]);

            return redirect()->back()->with('success', 'Asistencia actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la asistencia: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        
        return redirect()->back()->with('success', 'Asistencia eliminada correctamente');
    }
}
