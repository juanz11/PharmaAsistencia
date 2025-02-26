<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::where('role', 'employee')->paginate(10);
        return view('admin.dashboard', compact('users'));
    }

    public function users()
    {
        $users = User::where('role', 'employee')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function attendances()
    {
        $attendances = \App\Models\Attendance::with('user')
            ->latest('check_in_time')
            ->paginate(15);
        
        return view('admin.attendances.index', compact('attendances'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'identification' => 'required|string|unique:users',
            'location' => 'required|string',
            'join_date' => 'required|date',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'identification' => $validated['identification'],
            'location' => $validated['location'],
            'join_date' => $validated['join_date'],
            'role' => 'employee',
        ]);

        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'identification' => 'required|string|unique:users,identification,' . $user->id,
            'location' => 'required|string',
            'join_date' => 'required|date',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Usuario eliminado exitosamente.');
    }
}
