<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'identification' => ['required', 'string', 'max:255', 'unique:users'],
            'location' => ['required', 'string'],
            'join_date' => ['required', 'date'],
            'status' => ['required', 'in:active,inactive,pending'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'identification' => $validated['identification'],
            'location' => $validated['location'],
            'join_date' => $validated['join_date'],
            'status' => $validated['status'],
        ]);

        return response()->json(['user' => $user], 201);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'identification' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'location' => ['required', 'string'],
            'join_date' => ['required', 'date'],
            'status' => ['required', 'in:active,inactive,pending'],
        ]);

        $user->update($validated);

        return response()->json(['user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
