<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'identification' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'location' => ['required', 'string', 'max:255'],
            'current_password' => ['required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Actualizar datos básicos
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->identification = $validated['identification'];
        $user->location = $validated['location'];

        // Actualizar contraseña si se proporcionó una nueva
        if (isset($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Perfil actualizado correctamente.');
    }
}
