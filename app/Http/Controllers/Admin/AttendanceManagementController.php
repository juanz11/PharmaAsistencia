<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;

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
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.attendance.user', compact('user', 'attendances'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $attendance->update([
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
        ]);

        return redirect()->back()->with('success', 'Asistencia actualizada correctamente');
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        
        return redirect()->back()->with('success', 'Asistencia eliminada correctamente');
    }
}
