<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '>', 1)->get();
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:read,edit,admin'
        ]);

        $permissions = [
            'can_read' => false,
            'can_edit' => false,
            'is_admin' => false
        ];

        switch($request->role) {
            case 'read':
                $permissions['can_read'] = true;
                break;
            case 'edit':
                $permissions['can_read'] = true;
                $permissions['can_edit'] = true;
                break;
            case 'admin':
                $permissions['can_read'] = true;
                $permissions['can_edit'] = true;
                $permissions['is_admin'] = true;
                break;
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'can_read' => $permissions['can_read'],
            'can_edit' => $permissions['can_edit'],
            'is_admin' => $permissions['is_admin'],
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
            $userData['password_visible'] = $request->password;
        }

        $user->update($userData);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(User $user)
    {
        if ($user->id === 1) {
            return response()->json([
                'message' => 'El usuario administrador no puede ser eliminado'
            ], 403);
        }

        try {
            $user->delete();
            return response()->json([
                'message' => 'Usuario eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el usuario'
            ], 500);
        }
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function verifyAdminPassword(Request $request)
    {
        $admin = User::where('email', 'admin@admin.com')->first();
        
        if (Hash::check($request->password, $admin->password)) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false]);
    }
} 