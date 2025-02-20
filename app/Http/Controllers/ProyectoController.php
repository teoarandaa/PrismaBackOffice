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

    public function store(Request $request)
    {
        $request->validate([
            'nombre_proyecto' => 'required',
            'id_cliente' => 'required|exists:clientes,id',
            'tipo' => 'required|in:Web,App',
            'estado' => 'required|in:En progreso,Completado,Cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date',
            'presupuesto' => 'nullable|numeric',
            'link' => 'nullable|url'
        ]);

        $data = $request->all();
        
        // Si el estado es Completado, establecer la fecha de completado
        if ($request->estado === 'Completado') {
            $data['fecha_completado'] = now();
        }

        $proyecto = Proyecto::create($data);

        return response()->json([
            'message' => 'Proyecto creado correctamente',
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
            'nombre_proyecto' => 'required',
            'tipo' => 'required|in:Web,App',
            'estado' => 'required|in:En progreso,Completado,Cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date',
            'presupuesto' => 'nullable|numeric',
            'link' => 'nullable|url'
        ]);

        $data = $request->all();

        // Si el proyecto se marca como completado y no tenía fecha de completado
        if ($request->estado === 'Completado' && !$proyecto->fecha_completado) {
            $data['fecha_completado'] = now();
        }
        // Si el proyecto deja de estar completado, eliminar la fecha de completado
        elseif ($request->estado !== 'Completado') {
            $data['fecha_completado'] = null;
        }

        $proyecto->update($data);

        return response()->json([
            'message' => 'Proyecto actualizado correctamente',
            'proyecto' => $proyecto
        ]);
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
            'nombre_proyecto' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:En progreso,Completado,Cancelado',
            'tipo' => 'required|in:Web,App',
            'fecha_inicio' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date',
            'presupuesto' => 'nullable|numeric',
            'link' => 'nullable|url',
            'id_cliente' => 'required|exists:clientes,id'
        ]);
    }
} 