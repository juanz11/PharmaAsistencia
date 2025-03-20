<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RoleController extends Controller
{
    public function toggleRole()
    {
        $user = auth()->user();
        
        // Solo permitir el cambio si el email es uraharzamora@gmail.com
        if ($user->email !== 'uraharazamora@gmail.com') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para realizar esta acción'
            ], 403);
        }

        // Cambiar el rol
        $newRole = $user->role === 'admin' ? 'employee' : 'admin';
        $user->role = $newRole;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Rol actualizado correctamente',
            'role' => $newRole
        ]);
    }
}
