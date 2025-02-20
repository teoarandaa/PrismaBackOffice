<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 20);
        $clientes = Cliente::with('proyectos')
                          ->orderBy('created_at', 'desc')
                          ->paginate($perPage);

        return view('clientes.index', compact('clientes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'email' => 'required|email|unique:clientes',
                'telefono' => 'nullable|string|max:255',
                'ciudad' => 'nullable|string|max:255',
                'codigo_postal' => 'nullable|string|max:255',
                'pais' => 'nullable|string|max:255',
                'empresa' => 'nullable|string|max:255'
            ]);

            $cliente = Cliente::create($request->all());
            return response()->json($cliente, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el cliente', 'error' => $e->getMessage()], 422);
        }
    }

    public function show(Cliente $cliente)
    {
        try {
            $cliente->load('proyectos');
            return view('clientes.show', compact('cliente'));
        } catch (\Exception $e) {
            \Log::error('Error mostrando cliente: ' . $e->getMessage());
            return redirect()->route('clientes.index')
                ->with('error', 'Error al mostrar los detalles del cliente');
        }
    }

    public function update(Request $request, Cliente $cliente)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'email' => 'required|email|unique:clientes,email,' . $cliente->id,
                'telefono' => 'nullable|string|max:255',
                'ciudad' => 'nullable|string|max:255',
                'codigo_postal' => 'nullable|string|max:255',
                'pais' => 'nullable|string|max:255',
                'empresa' => 'nullable|string|max:255'
            ]);

            $cliente->update($validated);
            
            return response()->json([
                'message' => 'Cliente actualizado correctamente',
                'cliente' => $cliente
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error actualizando cliente: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al actualizar el cliente',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Request $request, Cliente $cliente)
    {
        try {
            // Verificar que el usuario tiene permisos de edición o es admin
            if (!auth()->user()->can_edit && !auth()->user()->is_admin) {
                return response()->json([
                    'message' => 'No tienes permisos para eliminar clientes'
                ], 403);
            }

            // Eliminar los proyectos asociados y luego el cliente
            $cliente->proyectos()->delete();
            $cliente->delete();
            
            \Log::info('Cliente eliminado exitosamente', [
                'cliente_id' => $cliente->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Cliente y sus proyectos eliminados correctamente'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error eliminando cliente: ' . $e->getMessage(), [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'message' => 'Error al eliminar el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }
} 