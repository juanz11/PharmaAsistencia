<?php

namespace App\Http\Controllers\Admin;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserAttendanceExport;
use App\Exports\AttendanceRangeExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceManagementController extends Controller
{
    public function exportRange(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        return Excel::download(
            new AttendanceRangeExport($startDate, $endDate),
            'asistencias_' . ($startDate ?? 'total') . '_a_' . ($endDate ?? 'actual') . '.xlsx'
        );
    }
    public function export($userId)
    {
        $user = User::findOrFail($userId);
        $attendances = $user->attendances()->orderBy('date', 'desc')->get();
        
        return Excel::download(new UserAttendanceExport($attendances), "asistencias_{$user->name}.xlsx");
    }
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
