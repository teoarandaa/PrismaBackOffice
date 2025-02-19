<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function index(Cliente $cliente)
    {
        $proyectos = $cliente->proyectos()->with('cliente')->get();
        return view('clientes.proyectos.index', compact('cliente', 'proyectos'));
    }

    public function store(Request $request, Cliente $cliente)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date',
            'estado' => 'required|in:En progreso,Completado,Cancelado',
            'tipo' => 'required|in:web,app',
            'presupuesto' => 'nullable|numeric',
            'link' => 'nullable|string'
        ]);

        $proyecto = $cliente->proyectos()->create([
            'nombre_proyecto' => $validatedData['nombre'],
            'descripcion' => $validatedData['descripcion'],
            'fecha_inicio' => $validatedData['fecha_inicio'],
            'fecha_finalizacion' => $validatedData['fecha_fin_estimada'],
            'estado' => $validatedData['estado'],
            'tipo' => $validatedData['tipo'],
            'presupuesto' => $validatedData['presupuesto'],
            'link' => $validatedData['link']
        ]);

        return response()->json(['message' => 'Proyecto creado correctamente']);
    }

    public function show(Cliente $cliente, Proyecto $proyecto)
    {
        return view('clientes.proyectos.show', compact('cliente', 'proyecto'));
    }

    public function update(Request $request, Cliente $cliente, Proyecto $proyecto)
    {
        // Log de los datos recibidos
        \Log::info('Datos recibidos en update:', $request->all());

        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'fecha_inicio' => 'nullable|date',
                'fecha_fin_estimada' => 'nullable|date',
                'presupuesto' => 'nullable|numeric',
                'estado' => ['required', 'string', 'in:En progreso,Completado,Cancelado'],
                'tipo' => ['required', 'string', 'in:web,app'],
                'link' => 'nullable|string'
            ]);

            \Log::info('Datos validados:', $validatedData);

            $proyecto->update([
                'nombre_proyecto' => $validatedData['nombre'],
                'descripcion' => $validatedData['descripcion'],
                'fecha_inicio' => $validatedData['fecha_inicio'],
                'fecha_finalizacion' => $validatedData['fecha_fin_estimada'],
                'presupuesto' => $validatedData['presupuesto'],
                'estado' => $validatedData['estado'],
                'tipo' => $validatedData['tipo'],
                'link' => $validatedData['link']
            ]);

            return response()->json([
                'message' => 'Proyecto actualizado correctamente',
                'proyecto' => $proyecto
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación:', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar proyecto:', [
                'message' => $e->getMessage(),
                'request' => $request->all()
            ]);
            return response()->json([
                'message' => 'Error al actualizar el proyecto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Cliente $cliente, Proyecto $proyecto)
    {
        try {
            if ($proyecto->id_cliente !== $cliente->id) {
                return response()->json(['message' => 'El proyecto no pertenece a este cliente'], 403);
            }

            $proyecto->delete();
            
            return response()->json([
                'message' => 'Proyecto eliminado correctamente',
                'proyecto_id' => $proyecto->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar proyecto: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al eliminar el proyecto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Cliente $cliente)
    {
        if (!auth()->user()->can_edit && !auth()->user()->is_admin) {
            abort(403, 'No tienes permisos para crear proyectos');
        }
        
        return view('clientes.proyectos.create', compact('cliente'));
    }

    public function edit(Cliente $cliente, Proyecto $proyecto)
    {
        return view('clientes.proyectos.edit', compact('cliente', 'proyecto'));
    }

    public function todos()
    {
        $proyectos = Proyecto::with('cliente')->get();
        return view('proyectos.todos', compact('proyectos'));
    }

    protected function validateProyecto(Request $request)
    {
        return $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'estado' => 'required|in:En progreso,Completado,Cancelado',
            'tipo' => 'required|in:web,app',
            // ... otros campos existentes
        ]);
    }
} 