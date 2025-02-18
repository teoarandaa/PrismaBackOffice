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
        $cliente->load('proyectos');
        return view('clientes.show', compact('cliente'));
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
        $cliente->delete();
        return response()->json(['message' => 'Cliente eliminado']);
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