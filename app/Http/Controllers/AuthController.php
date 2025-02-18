<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Eliminar tokens anteriores y crear uno nuevo
        $user->tokens()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function webLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/clientes');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput($request->except('password'));
    }

    public function webRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_visible' => $request->password,
            'can_read' => $permissions['can_read'],
            'can_edit' => $permissions['can_edit'],
            'is_admin' => $permissions['is_admin'],
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
    }

    public function webLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function webUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'can_read' => $request->has('can_read'),
            'can_edit' => $request->has('can_edit'),
            'is_admin' => $request->has('is_admin'),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
            $userData['password_visible'] = $request->password;
        }

        $user->update($userData);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function verifyAdminPassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'user_id' => 'required|exists:users,id'
        ]);

        if (Hash::check($request->password, auth()->user()->password)) {
            $user = User::findOrFail($request->user_id);
            return response()->json([
                'success' => true,
                'password' => $user->password_visible ?? 'No disponible'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Contraseña incorrecta'
        ], 401);
    }
} 