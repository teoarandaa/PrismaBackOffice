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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date',
            'presupuesto' => 'nullable|numeric',
            'estado' => 'required|in:En progreso,Completado,Cancelado',
            'link' => 'nullable|url',
            'tipo' => 'required|in:web,app',
        ]);

        $proyecto = $cliente->proyectos()->create([
            'nombre_proyecto' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_finalizacion' => $request->fecha_fin_estimada,
            'presupuesto' => $request->presupuesto,
            'estado' => $request->estado,
            'link' => $request->link,
            'tipo' => $request->tipo,
        ]);

        return response()->json([
            'message' => 'Proyecto creado exitosamente',
            'proyecto' => $proyecto
        ]);
    }

    public function show(Cliente $cliente, Proyecto $proyecto)
    {
        return view('clientes.proyectos.show', compact('cliente', 'proyecto'));
    }

    public function update(Request $request, Cliente $cliente, Proyecto $proyecto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date',
            'estado' => 'required|in:en_progreso,completado,cancelado',
            'presupuesto' => 'nullable|numeric',
            'link' => 'nullable|url',
            'tipo' => 'required|in:web,app',
        ]);

        try {
            $proyecto->update([
                'nombre_proyecto' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_finalizacion' => $request->fecha_fin_estimada,
                'presupuesto' => $request->presupuesto,
                'estado' => $request->estado === 'en_progreso' ? 'En progreso' : ucfirst($request->estado),
                'link' => $request->link,
                'tipo' => $request->tipo,
            ]);

            return response()->json([
                'message' => 'Proyecto actualizado exitosamente',
                'data' => $proyecto
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar proyecto: ' . $e->getMessage());
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
            return response()->json(['message' => 'Proyecto eliminado correctamente']);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar proyecto: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar el proyecto'], 500);
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