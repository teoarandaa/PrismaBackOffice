<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('proyectos')->get();
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

    public function destroy(Cliente $cliente)
    {
        try {
            // Verificar si tiene proyectos relacionados
            if ($cliente->proyectos()->count() > 0) {
                return response()->json([
                    'message' => 'No se puede eliminar el cliente porque tiene proyectos asociados'
                ], 422);
            }

            $cliente->delete();
            
            return response()->json([
                'message' => 'Cliente eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error eliminando cliente: ' . $e->getMessage());
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