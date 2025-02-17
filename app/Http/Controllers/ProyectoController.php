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
            'nombre' => 'required',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date',
            'estado' => 'nullable|in:en_progreso,completado,cancelado',
            'presupuesto' => 'nullable|numeric',
            'link' => 'nullable|string'
        ]);

        // Transformar los datos recibidos al formato esperado
        $datos = [
            'nombre_proyecto' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_finalizacion' => $request->fecha_fin_estimada,
            'presupuesto' => $request->presupuesto,
            'estado' => $request->estado === 'en_progreso' ? 'En progreso' : ucfirst($request->estado),
            'link' => $request->link
        ];

        \Log::info('Datos transformados:', $datos);
        \Log::info('Cliente ID:', ['id' => $cliente->id]);

        try {
            $proyecto = $cliente->proyectos()->create($datos);
            
            if (!$proyecto) {
                return response()->json(['error' => 'No se pudo crear el proyecto'], 500);
            }

            \Log::info('Proyecto creado:', $proyecto->toArray());
            return response()->json([
                'message' => 'Proyecto creado exitosamente',
                'data' => $proyecto
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error al crear proyecto:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al crear el proyecto',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Cliente $cliente, Proyecto $proyecto)
    {
        return view('clientes.proyectos.show', compact('cliente', 'proyecto'));
    }

    public function update(Request $request, Cliente $cliente, Proyecto $proyecto)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date',
            'estado' => 'nullable|in:en_progreso,completado,cancelado',
            'presupuesto' => 'nullable|numeric',
            'link' => 'nullable|string'
        ]);

        // Transformar los datos recibidos al formato esperado
        $datos = [
            'nombre_proyecto' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_finalizacion' => $request->fecha_fin_estimada,
            'presupuesto' => $request->presupuesto,
            'estado' => $request->estado === 'en_progreso' ? 'En progreso' : ucfirst($request->estado),
            'link' => $request->link
        ];

        try {
            $proyecto->update($datos);
            
            return response()->json([
                'message' => 'Proyecto actualizado exitosamente',
                'data' => $proyecto
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar proyecto:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al actualizar el proyecto',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Cliente $cliente, Proyecto $proyecto)
    {
        $proyecto->delete();
        return response()->json(['message' => 'Proyecto eliminado']);
    }

    public function create(Cliente $cliente)
    {
        return view('clientes.proyectos.create', compact('cliente'));
    }

    public function edit(Cliente $cliente, Proyecto $proyecto)
    {
        return view('clientes.proyectos.edit', compact('cliente', 'proyecto'));
    }
} 